@component('mail::message')

@lang('messages.sponsor.onboarding.salutation', ['name' => $user->fname])

@lang('messages.sponsor.onboarding.participate.opening')

@include('sponsors.onboarding.en.participate')

@lang('messages.sponsor.onboarding.learn_more')

@lang('messages.sponsor.onboarding.complete_guide')
@endcomponent
