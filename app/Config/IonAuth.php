<?php namespace Config;

class IonAuth extends \IonAuth\Config\IonAuth
{
    // set your specific config
    // public $siteTitle                = 'Pondasi2.test';       // Site Title, example.com
    // public $adminEmail               = 'admin@example.com'; // Admin Email, admin@example.com
    // public $emailTemplates           = 'App\\Views\\auth\\email\\';
    // ...

    /*
	 | -------------------------------------------------------------------------
	 | Authentication options.
	 | -------------------------------------------------------------------------
	 | maximumLoginAttempts: 	This maximum is not enforced by the library, but is used by
	 | 							is_max_login_attempts_exceeded().
	 | 							The controller should check this function and act appropriately.
	 | 							If this variable set to 0, there is no maximum.
	 | minPasswordLength:		This minimum is not enforced directly by the library.
	 | 							The controller should define a validation rule to enforce it.
	 | 							See the Auth controller for an example implementation.
	 |
	 | The library will fail for empty password or password size above 4096 bytes.
	 | This is an arbitrary (long) value to protect against DOS attack.
	 */
	public $siteTitle                = 'pondasi.test';       // Site Title, example.com
	public $adminEmail               = 'mail@naufalhakim.my.id'; // Admin Email, admin@example.com
	public $defaultGroup             = 'members';           // Default group, use name
	public $adminGroup               = 'admin';             // Default administrators group, use name
	public $identity                 = 'email';             /* You can use any unique column in your table as identity column.
																	IMPORTANT: If you are changing it from the default (email),
																				update the UNIQUE constraint in your DB */
	public $minPasswordLength        = 8;                   // Minimum Required Length of Password (not enforced by lib - see note above)
	public $emailActivation          = false;               // Email Activation for registration
	public $manualActivation         = false;               // Manual Activation for registration
	public $rememberUsers            = true;                // Allow users to be remembered and enable auto-login
	public $userExpire               = 86500;               // How long to remember the user (seconds). Set to zero for no expiration
	public $userExtendonLogin        = false;               // Extend the users cookies every time they auto-login
	public $trackLoginAttempts       = true;                // Track the number of failed login attempts for each user or ip.
	public $trackLoginIpAddress      = true;                // Track login attempts by IP Address, if false will track based on identity. (Default: true)
	public $maximumLoginAttempts     = 3;                   // The maximum number of failed login attempts.
	public $lockoutTime              = 600;                 /* The number of seconds to lockout an account due to exceeded attempts
																	You should not use a value below 60 (1 minute) */
	public $forgotPasswordExpiration = 1800;                /* The number of seconds after which a forgot password request will expire. If set to 0, forgot password requests will not expire.
																	30 minutes to 1 hour are good values (enough for a user to receive the email and reset its password)
																	You should not set a value too high, as it would be a security issue! */
	public $recheckTimer             = 0;                   /* The number of seconds after which the session is checked again against database to see if the user still exists and is active.
																	Leave 0 if you don't want session recheck. if you really think you need to recheck the session against database, we would
																	recommend a higher value, as this would affect performance */

	/**
	 * Cookie options.
	 * rememberCookieName Default: remember_code
	 *
	 * @var string
	 */
	public $rememberCookieName = 'remember_code';


	/**
	 * Email templates.
	 * Folder where email templates are stored.
	 * Default: IonAuth\\Views\\auth\\email\\
	 *
	 * @var string
	 */
	public $emailTemplates = 'App\\Views\\auth\\email\\';
}