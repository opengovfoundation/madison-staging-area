@component('mail::message')

@lang('messages.sponsor.onboarding.salutation', ['name' => $user->fname])


@lang('messages.sponsor.onboarding.prepare.opening')


@lang('messages.sponsor.onboarding.prepare.opening_2')


@include('sponsors.onboarding.'.\Lang::getLocale().'.prepare')


# @lang('messages.sponsor.onboarding.learn_more')


@lang('messages.sponsor.onboarding.complete_guide', ['guideLink' => route('sponsors.guide')])
@endcomponent
