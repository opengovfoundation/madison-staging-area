@component('mail::message')

@lang('messages.sponsor.onboarding.salutation', ['name' => $user->fname])

@lang('messages.sponsor.onboarding.publish.opening', ['sponsor' => $sponsor->display_name])

@lang('messages.sponsor.onboarding.publish.opening_2')

@include('sponsors.onboarding.en.publish')

@lang('messages.sponsor.onboarding.learn_more')

@lang('messages.sponsor.onboarding.complete_guide')
@endcomponent
