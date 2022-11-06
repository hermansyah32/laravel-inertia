@component('mail::message')
# @lang('Hello!')

@lang('There has been a failed login attempt to your :app account.', ['app' => config('app.name')])

@component('mail::table')
{{ '|        |          |' }}
{{ '| ------ | -------- |' }}
{{ '| ' . trans('Account') . ' | : ' . $account->email . ' |' }}
@if ($location && $location['default'] === false)
{{ '| ' . trans('City') . ' | : ' . $location['city'] ?? __('Unknown City') . ' |'}}
{{ '| ' . trans('State') . ' | : ' . $location['state'], __('Unknown State') . ' |'}}
@endif
{{ '| ' . trans('Time Login') . ' | : ' . $time->toCookieString() . ' |'}}
{{ '| ' . trans('Browser') . ' | : ' . $browser . ' |'}}
@endcomponent

@lang('If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your password.')<br/>

@lang('Regards')<br/>
{{ config('app.name') }}
@endcomponent
