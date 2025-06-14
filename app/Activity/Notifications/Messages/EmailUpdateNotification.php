<?php

namespace BookStack\Activity\Notifications\Messages;

use BookStack\Activity\Notifications\MessageParts\EntityLinkMessageLine;
use BookStack\Activity\Notifications\MessageParts\ListMessageLine;

use BookStack\Activity\Notifications\MessageParts\ApplicationLinkLine;
use BookStack\Entities\Models\Page;
use BookStack\Users\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use BookStack\App\MailNotification;
use Illuminate\Bus\Queueable;

class EmailUpdateNotification extends MailNotification
{
    use Queueable;

    public function __construct(
        protected $detail = [],
        protected User $user,
    ) {
    }

    public function toMail(User $notifiable): MailMessage
    {
        /** @var Page $page */
        $pages = $this->detail;

        $locale = $notifiable->getLocale();
        $listLines = array();

        foreach ($pages as $page){
            $listLines[] = new EntityLinkMessageLine($page);

        }

        // $listLines = array_filter([
        //     $locale->trans('notifications.detail_page_name') => new EntityLinkMessageLine($page),
        //     $locale->trans('notifications.detail_page_path') => $this->buildPagePathLine($page, $notifiable),
        //     $locale->trans('notifications.detail_updated_by') => $this->user->name,
        // ]);

        return $this->newMailMessage($locale)
            ->subject($locale->trans('notifications.updated_email_subject', ['date' => date("d-m-Y")]))
            ->line($locale->trans('notifications.updated_email_intro'))
            ->line(new ListMessageLine($listLines))
            ->line(new ApplicationLinkLine())
            ->line($locale->trans('notifications.updated_email_debounce'))
            ->line($locale->trans('notifications.updated_email_regards'))
            ->line($locale->trans('notifications.updated_email_team'));
            
    }

     /**
     * Build the common reason footer line used in mail messages.
     */
    protected function buildReasonFooterLine(LocaleDefinition $locale): LinkedMailMessageLine
    {
        return new LinkedMailMessageLine(
            url('/my-account/notifications'),
            $locale->trans('notifications.footer_reason'),
            $locale->trans('notifications.footer_reason_link'),
        );
    }
}
