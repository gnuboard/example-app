<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('['.config('app.name').'] 이메일 주소 인증')
            ->greeting('안녕하세요!')
            ->line('아래 버튼을 클릭하여 이메일 주소를 인증해 주세요.')
            ->action('이메일 인증하기', $verificationUrl)
            ->line('만약 회원가입을 하지 않으셨다면, 이 메일을 무시하셔도 됩니다.')
            ->salutation('감사합니다.');
    }
}
