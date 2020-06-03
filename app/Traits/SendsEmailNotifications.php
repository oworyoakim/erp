<?php


namespace App\Traits;


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Swift_TransportException;

trait SendsEmailNotifications
{
    /**
     * @param string $email
     * @param string$fullName
     * @param string $reminderCode
     * @throws Swift_TransportException
     */
    protected function sendPasswordResetReminderEmail($email, $fullName, $reminderCode)
    {
        $companyName = settings()->get('company_name');
        $companyEmail = settings()->get('company_email');
        $subject = settings()->get('reset_password_email_subject');
        $subject = str_replace('{companyName}', $companyName, $subject);
        $resetPasswordLink = url('/reset-password/' . $email . '/' . $reminderCode);

        $htmlMessage = settings()->get('reset_password_email_template');

        if (!empty($htmlMessage))
        {
            $htmlMessage = str_replace('{userFullName}', $fullName, $htmlMessage);
            $htmlMessage = str_replace('{companyName}', $companyName, $htmlMessage);
            $htmlMessage = str_replace('{passwordResetLink}', $resetPasswordLink, $htmlMessage);
        } else
        {
            $data = array(
                'subject' => $subject,
                'fullName' => $fullName,
                'companyName' => $companyName,
                'resetPasswordLink' => $resetPasswordLink
            );
            $htmlMessage = View::make('emails.reset-password', $data)->render();
        }

        Mail::raw($htmlMessage, function ($message) use ($email, $companyName, $companyEmail, $subject) {
            $message->from($companyEmail, $companyName);
            $message->to($email);
            $headers = $message->getHeaders();
            $message->setContentType('text/html');
            $message->setSubject($subject);
        });
    }

}
