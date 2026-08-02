<div class="fastora-login">
    <div class="fastora-login__form-panel">
        <div class="fastora-login__form-inner">
            <div class="fastora-login__brand">
                <x-filament-panels::logo />
            </div>

            <div class="fastora-login__intro">
                <h1>{{ $this->getHeading() }}</h1>

                @if (filled($subheading = $this->getSubheading()))
                    <p>{{ $subheading }}</p>
                @endif
            </div>

            {{ $this->content }}
        </div>
    </div>

    <div class="fastora-login__art" aria-hidden="true"></div>

    <style>
    .fastora-login {
        --fastora-login-panel: min(46%, 620px);
        position: relative;
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        padding-right: calc(var(--fastora-login-panel) + 4rem);
        background: #ffffff;
    }

    .dark .fastora-login {
        background: rgb(24 24 27);
    }

    .fastora-login__art {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        bottom: 1.5rem;
        width: var(--fastora-login-panel);
        border-radius: 32px;
        pointer-events: none;
        background:
            radial-gradient(42% 38% at 72% 26%, rgba(150, 200, 255, 0.95), transparent 60%),
            radial-gradient(48% 42% at 28% 70%, rgba(90, 160, 255, 0.9), transparent 62%),
            radial-gradient(65% 60% at 55% 52%, #2b7fd6, transparent 72%),
            linear-gradient(150deg, #3f9ae0 0%, #163b70 100%);
        box-shadow: 0 40px 90px -35px rgba(22, 59, 112, 0.65);
    }

    .fastora-login__form-panel {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 400px;
    }

    .fastora-login__form-inner {
        animation: fastora-login-in 0.4s ease-out;
    }

    @keyframes fastora-login-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .fastora-login__form-inner {
            animation: none;
        }
    }

    .fastora-login__brand {
        display: flex;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .fastora-login__brand img,
    .fastora-login__brand svg {
        height: 40px;
        width: auto;
    }

    .fastora-login__intro {
        margin-bottom: 1.75rem;
        text-align: center;
    }

    .fastora-login__intro h1 {
        font-size: 1.9rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin: 0 0 0.35rem;
        color: rgb(24 24 27);
    }

    .dark .fastora-login__intro h1 {
        color: rgb(250 250 250);
    }

    .fastora-login__intro p {
        margin: 0;
        line-height: 1.5;
        color: rgb(113 113 122);
    }

    @media (max-width: 900px) {
        .fastora-login {
            padding: 2rem 1.5rem;
            padding-right: 1.5rem;
        }

        .fastora-login__art {
            display: none;
        }

        .fastora-login__form-panel {
            margin: 0 auto;
        }
    }
    </style>
</div>
