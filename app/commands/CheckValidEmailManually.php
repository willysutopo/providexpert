<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckValidEmailManually extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'email:checkvalidemailmanually';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check Regularly Whether An Email Is Valid ( manually )';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		require_once ( public_path() . "/assets/verifyemail/class.verifyEmail.php" );

		$vmail = new verifyEmail();

		// get all members' emails whose status is not verified yet
		$members = Members::where('is_email_verified', 0)->get();

		// content header
		$content = "EMAIL CHECKING STATUS MANUALLY AT " . date("Y-m-d H:i:s") . "\n";
		$content .= "==================================================================\n";
		$file_path = storage_path().'/logs/email.log';

		File::append($file_path, $content);

		foreach( $members as $member )
		{
			$correct = true;

			$email = $member->email;
			if ($vmail->check($email)) 
			{
				// echo 'email &lt;' . $email . '&gt; exist!<br><br>';
			} 
			else 
			if ($vmail->isValid($email)) 
			{
				$correct = false;
				// echo 'email &lt;' . $email . '&gt; valid, but not exist!<br><br>';
			} 
			else 
			{
				$correct = false;
				// echo 'email &lt;' . $email . '&gt; not valid and not exist!<br><br>';
			}

			// if email is incorrect
			if ( $correct == false )
			{
				// format of data
				$content = "EMAIL : " . $email . "'s STATUS IS : INVALID AT : " . date("Y-m-d H:i:s") . "\n";
				$file_path = storage_path().'/logs/email.log';

				File::append($file_path, $content);

				// change the status of member to rejected
				$rejected_member = Members::where('email', $email)->update(array('is_email_verified' => 2));
			}
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			
		);
	}

}
