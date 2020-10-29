<?php

namespace Nodes\Backend\Models\User;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Nodes\Backend\Auth\Exceptions\InvalidPasswordException;
use Weatherbys\Database\Eloquent\Repository;
use Weatherbys\Database\Exceptions\SaveFailedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UserRepository.
 */
class UserRepository extends Repository
{
    /**
     * Constructor.
     *
     * @throws \Nodes\Backend\Auth\Exception\InvalidUserModelException
     */
    public function __construct()
    {
        $this->setupRepository(app('nodes.backend.auth.model'));
    }

    /**
     * Retrieve user by manager data or create a new user.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param  array $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     * @throws \Nodes\Database\Exceptions\SaveFailedException
     */
    public function loginUserFromManager(array $data)
    {
        // Retrieve user by e-mail
        $user = $this->getBy('email', $data['email']);
        if (! empty($user)) {
            // Set image if its different
            if (empty($user->image)) {
                $user->image = $data['image'];
                $user->save();
            }

            return $user;
        }

        // Only create separate users if config is set
        if (config('nodes.backend.manager.separate_users', false)) {
            try {
                return $this->createUser([
                    'name'      => $data['name'],
                    'email'     => $data['email'],
                    'user_role' => config('nodes.backend.manager.role', 'developer'),
                    'password'  => Str::random(16),
                ]);
            } catch (Exception $e) {
                // Do nothing
            }
        }

        return $this->getManagerUser();
    }

    /**
     * Retrieve user by ID and "remember me" token.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  int $id
     * @param  string  $token
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByIdAndRememberToken($id, $token)
    {
        return $this->where('id', $id)
                    ->where($this->getModel()->getRememberTokenName(), $token)
                    ->first();
    }

    /**
     * Retrieve user by e-mail and password.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param  string $email
     * @param  string $password
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Backend\Auth\Exceptions\InvalidPasswordException
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function getByEmailAndPassword($email, $password)
    {
        // Retrieve user by e-mail
        $user = $this->getByOrFail('email', $email);

        // Validate password
        if (! \Hash::check($password, $user['password'])) {
            throw new InvalidPasswordException('Password was incorrect. Try again.');
        }

        return $user;
    }

    /**
     * Retrieve all users paginated
     * sorted by name ASC.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param  int $limit
     * @param  array   $fields
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated($limit = 25, $fields = ['*'])
    {
        $query = $this->orderBy('name', 'ASC');

        // Apply search conditions if search is set
        if (Request::get('search', null)) {
            $query->where(function ($query) {
                $query->orWhere('name', 'LIKE', '%'.Request::get('search').'%')
                      ->orWhere('email', 'LIKE', '%'.Request::get('search').'%');
            });
        }

        return $query->paginate($limit, $fields);
    }

    /**
     * Retrieve manager user from config.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function getManagerUser()
    {
        return $this->getByOrFail('email', config('nodes.backend.manager.email'));
    }

    /**
     * Create a user from validated data.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param  array $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     * @throws \Nodes\Database\Exceptions\SaveFailedException
     */
    public function createUser(array $data)
    {
        try {
            // Begin transaction
            $this->beginTransaction();

            // Generate new user instance
            $user = $this->create($data);

            // Add image to user if one has been uploaded
            if (! empty($data['image']) && $data['image'] instanceof UploadedFile) {
                try {
                    $imagePath = assets_add_uploaded_file($data['image'], 'backend_user_images');
                    $user->update(['image' => $imagePath]);
                } catch (Exception $e) {
                    // Do nothing
                }
            }

            // Generate token for user
            $user->createToken();
        } catch (Exception $e) {
            $this->rollbackTransaction();
            throw new SaveFailedException(sprintf('Could not create new user. Reason %s', $e->getMessage()));
        }

        // Commit transaction
        $this->commitTransaction();

        return $user;
    }

    /**
     * Send a welcome email.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param  \Illuminate\Database\Eloquent\Model $user
     * @param  string                              $password
     * @return bool
     */
    public function sendWelcomeMail(Model $user, $password = '******')
    {
        return (bool) Mail::send([
            'html' => config('nodes.backend.welcome.views.html', 'nodes.backend::backend-users.emails.html'),
            'text' => config('nodes.backend.welcome.views.text', 'nodes.backend::backend-users.emails.text'),
        ], [
            'user' => $user,
            'url' => route(config('nodes.backend.welcome.route')),
            'project' => config('nodes.project.name'),
            'password' => $password,

        ], function ($message) use ($user) {
            $message->to($user->email)
                     ->from(config('nodes.backend.welcome.from.email', 'no-reply@like.st'), config('nodes.backend.welcome.from.name', 'Backend'))
                     ->subject(config('nodes.backend.welcome.subject', 'Welcome to backend'));
        });
    }

    /**
     * Update user with validated data.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param  \Illuminate\Database\Eloquent\Model $user
     * @param  array                               $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\SaveFailedException
     */
    public function updateUser(Model $user, array $data)
    {
        // If no password is set, we'll need to
        // remove 'password' and 'password_repeat' from $data
        // before we start filling, so we don't override it.
        if (empty($data['password'])) {
            unset($data['password'], $data['password_repeat']);
        }

        // Fill with new data
        $user->update($data);

        // Add image to user if one has been uploaded
        if (! empty($data['image']) && $data['image'] instanceof UploadedFile) {
            try {
                $imagePath = assets_add_uploaded_file($data['image'], 'backend_user_images');
                $user->update(['image' => $imagePath]);
            } catch (Exception $e) {
                // Do nothing
            }
        } elseif (empty($data['file_picker_file_name']) && !empty($user->image)) {
            // Remove profile image
            $user->update(['image' => null]);
        }

        // Return
        return $user;
    }

    /**
     * Retrieve user by a multiple columns.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getByColumns(array $columns)
    {
        foreach ($columns as $column => $value) {
            // Ignore sensitive columns
            if (in_array($column, $this->getModel()->getHidden())) {
                continue;
            }

            // Add column to WHERE clause
            $this->where($column, '=', $value);
        }

        return $this->first();
    }
}
