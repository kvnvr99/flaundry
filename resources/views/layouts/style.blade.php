<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico')}}">

<!-- Plugins css -->
<link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />

<!-- App css -->
<link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

<link href="{{ asset('assets/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet"  disabled />

<!-- icons -->
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
@stack('style')
<style>
    #loading {
        position: absolute;
        width: 100%;
        height: 100%;
        display: none;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2;
    }
    .lds-hourglass,
    .lds-hourglass:after {
        box-sizing: border-box;
    }
    .lds-hourglass {
        display: block;
        width: 80px;
        height: 80px;
        z-index: 4;
        top: 36%;
        right: 50%;
        transform: translate(50%, 50%);
        position: fixed;

    }
    .lds-hourglass:after {
        content: " ";
        display: block;
        border-radius: 50%;
        width: 0;
        height: 0;
        margin: 8px;
        box-sizing: border-box;
        border: 32px solid currentColor;
        border-color: currentColor transparent currentColor transparent;
        animation: lds-hourglass 1.2s infinite;
    }
    @keyframes lds-hourglass {
        0% {
            transform: rotate(0);
            animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
        }
        50% {
            transform: rotate(900deg);
            animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        }
        100% {
            transform: rotate(1800deg);
        }
    }
</style>
