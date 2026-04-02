<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject', config('app.name'))</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color: {{ $primaryColor ?? '#08c' }}; padding: 24px 30px; text-align: center;">
                            @if($logoUrl ?? false)
                                <img src="{{ $logoUrl }}" alt="{{ $siteName ?? config('app.name') }}" style="max-height: 40px; max-width: 200px;">
                            @else
                                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: bold;">{{ $siteName ?? config('app.name') }}</h1>
                            @endif
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 30px;">
                            {{-- Greeting --}}
                            @if($greeting ?? false)
                                <h2 style="color: #333; margin: 0 0 16px 0; font-size: 20px;">{{ $greeting }}</h2>
                            @endif

                            {{-- Content --}}
                            <div style="color: #555; font-size: 15px; line-height: 1.6;">
                                @yield('content')
                            </div>

                            {{-- CTA Button --}}
                            @if($actionUrl ?? false)
                                <div style="text-align: center; margin: 24px 0;">
                                    <a href="{{ $actionUrl }}" style="display: inline-block; padding: 12px 30px; background-color: {{ $primaryColor ?? '#08c' }}; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 15px;">
                                        {{ $actionText ?? 'View Details' }}
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8f8f8; padding: 20px 30px; border-top: 1px solid #eee; text-align: center;">
                            <p style="color: #999; font-size: 12px; margin: 0 0 8px 0;">
                                {{ $siteName ?? config('app.name') }} &mdash; {{ $address ?? '' }}
                            </p>
                            <p style="color: #999; font-size: 12px; margin: 0;">
                                &copy; {{ date('Y') }} {{ $siteName ?? config('app.name') }}. All rights reserved.
                            </p>
                            @if($unsubscribeUrl ?? false)
                                <p style="margin: 8px 0 0 0;">
                                    <a href="{{ $unsubscribeUrl }}" style="color: #999; font-size: 11px; text-decoration: underline;">Unsubscribe</a>
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
