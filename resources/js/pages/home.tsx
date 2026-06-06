import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';

const NICHES = ['Tech', 'Comedy', 'Finance', 'Fitness', 'Food', 'Lifestyle', 'Gaming', 'Education'] as const;
const PLATFORMS = ['YouTube', 'Instagram', 'Both'] as const;
const LANGUAGES = ['Hindi', 'English', 'Hinglish', 'Tamil', 'Telugu'] as const;

function FieldError({ children }: { children: React.ReactNode }) {
    return (
        <div className="mt-[7px] flex items-center gap-1.5 text-[13px] font-medium text-[#dc2626]">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" className="shrink-0">
                <circle cx="8" cy="8" r="7" stroke="#dc2626" strokeWidth="1.4" />
                <path d="M8 4.6v4.2M8 11.1v.05" stroke="#dc2626" strokeWidth="1.6" strokeLinecap="round" />
            </svg>
            {children}
        </div>
    );
}

function inputCls(hasError: boolean) {
    const base =
        'h-[46px] w-full rounded-lg border bg-white px-3.5 text-[15.5px] font-medium text-[#1b1b18] placeholder:font-normal placeholder:text-[#a3a097] outline-none transition-[border-color,box-shadow]';
    return hasError
        ? `${base} border-[#dc2626] ring-[3px] ring-[#dc2626]/15`
        : `${base} border-[#e4e2db] focus:border-[#F4730E] focus:ring-[3px] focus:ring-[#F4730E]/20`;
}

function selectCls(hasError: boolean, empty: boolean) {
    const base =
        'h-[46px] w-full appearance-none rounded-lg border bg-white px-3.5 pr-10 text-[15.5px] outline-none transition-[border-color,box-shadow]';
    const colorCls = empty ? 'font-normal text-[#a3a097]' : 'font-medium text-[#1b1b18]';
    const stateCls = hasError
        ? 'border-[#dc2626] ring-[3px] ring-[#dc2626]/15'
        : 'border-[#e4e2db] focus:border-[#F4730E] focus:ring-[3px] focus:ring-[#F4730E]/20';
    return `${base} ${colorCls} ${stateCls}`;
}

function Field({
    label,
    children,
    error,
    last = false,
}: {
    label: string;
    children: React.ReactNode;
    error?: string;
    last?: boolean;
}) {
    return (
        <div className={last ? '' : 'mb-[18px]'}>
            <label className="mb-[7px] block text-[14px] font-medium text-[#1b1b18]">{label}</label>
            {children}
            {error && <FieldError>{error}</FieldError>}
        </div>
    );
}

function SelectField({
    id,
    value,
    onChange,
    options,
    placeholder,
    hasError = false,
}: {
    id: string;
    value: string;
    onChange: (v: string) => void;
    options: readonly string[];
    placeholder: string;
    hasError?: boolean;
}) {
    return (
        <div className="relative">
            <select
                id={id}
                name={id}
                value={value}
                onChange={(e) => onChange(e.target.value)}
                className={selectCls(hasError, !value)}
                aria-invalid={hasError || undefined}
            >
                <option value="" disabled>
                    {placeholder}
                </option>
                {options.map((o) => (
                    <option key={o} value={o}>
                        {o}
                    </option>
                ))}
            </select>
            <svg
                width="16"
                height="16"
                viewBox="0 0 16 16"
                fill="none"
                className="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2"
            >
                <path
                    d="M4 6l4 4 4-4"
                    stroke="#76746c"
                    strokeWidth="1.6"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                />
            </svg>
        </div>
    );
}

function SuccessPanel({ email }: { email: string }) {
    return (
        <div className="mt-10">
            <div className="mb-[22px] flex h-[46px] w-[46px] items-center justify-center rounded-full bg-[#F4730E]/10">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M5 12.5l4.2 4.2L19 7"
                        stroke="#F4730E"
                        strokeWidth="2.4"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                    />
                </svg>
            </div>
            <h2 className="text-[27px] font-bold leading-tight tracking-[-0.02em] text-[#1b1b18]">Check your inbox!</h2>
            <p className="mt-[14px] text-[16px] leading-[1.55] text-[#1b1b18]">
                We sent a confirmation to <strong className="font-semibold">{email}</strong>. Click the link to activate
                your brief.
            </p>
            <p className="mt-4 text-[13.5px] leading-[1.5] text-[#76746c]">
                Check spam if you don't see it in 2 minutes.
            </p>
        </div>
    );
}

export default function Home() {
    const [submitted, setSubmitted] = useState(false);
    const [rateLimited, setRateLimited] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        niche: '',
        platform: '',
        language: '',
        whatsapp_number: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        setRateLimited(false);
        post('/subscribe', {
            onSuccess: () => setSubmitted(true),
            onError: (errs) => {
                if (Object.keys(errs).length === 0) {
                    setRateLimited(true);
                }
            },
        });
    }

    return (
        <>
            <Head title="BriefWala — Weekly content ideas for Indian creators" />
            <div className="flex min-h-screen flex-col bg-[#FDFDFC] px-8 py-10 lg:justify-center">
                <div className="mx-auto w-full max-w-[416px]">
                    <div className="mb-[34px]">
                        <div
                            className="leading-none tracking-[-0.02em] text-[#1b1b18]"
                            style={{ fontSize: submitted ? 28 : 40, fontWeight: 700 }}
                        >
                            Brief<span className="text-[#F4730E]">Wala</span>
                        </div>
                        {!submitted && (
                            <p className="mt-[18px] text-[18px] font-medium leading-[1.45] tracking-[-0.01em] text-pretty text-[#1b1b18]">
                                Your Monday content ideas —{' '}
                                <span className="text-[#76746c]">researched for you.</span>
                            </p>
                        )}
                    </div>

                    {submitted ? (
                        <SuccessPanel email={data.email} />
                    ) : (
                        <>
                            <form onSubmit={submit} noValidate>
                                <Field label="Email" error={errors.email}>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        placeholder="you@example.com"
                                        autoFocus
                                        autoComplete="email"
                                        className={inputCls(!!errors.email)}
                                        aria-invalid={errors.email ? true : undefined}
                                    />
                                </Field>

                                <Field label="Niche" error={errors.niche}>
                                    <SelectField
                                        id="niche"
                                        value={data.niche}
                                        onChange={(v) => setData('niche', v)}
                                        options={NICHES}
                                        placeholder="Choose your niche"
                                        hasError={!!errors.niche}
                                    />
                                </Field>

                                <Field label="Platform" error={errors.platform}>
                                    <SelectField
                                        id="platform"
                                        value={data.platform}
                                        onChange={(v) => setData('platform', v)}
                                        options={PLATFORMS}
                                        placeholder="Where you post"
                                        hasError={!!errors.platform}
                                    />
                                </Field>

                                <Field label="Language" error={errors.language}>
                                    <SelectField
                                        id="language"
                                        value={data.language}
                                        onChange={(v) => setData('language', v)}
                                        options={LANGUAGES}
                                        placeholder="Brief language"
                                        hasError={!!errors.language}
                                    />
                                </Field>

                                <Field label="WhatsApp (optional)" error={errors.whatsapp_number} last>
                                    <input
                                        id="whatsapp_number"
                                        type="tel"
                                        name="whatsapp_number"
                                        value={data.whatsapp_number}
                                        onChange={(e) => setData('whatsapp_number', e.target.value)}
                                        placeholder="+91 98765 43210"
                                        autoComplete="tel"
                                        className={inputCls(!!errors.whatsapp_number)}
                                        aria-invalid={errors.whatsapp_number ? true : undefined}
                                    />
                                </Field>

                                {rateLimited && (
                                    <div className="mt-5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-3">
                                        <FieldError>Too many attempts. Please wait a minute and try again.</FieldError>
                                    </div>
                                )}

                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="mt-6 flex h-[50px] w-full cursor-pointer items-center justify-center gap-2.5 rounded-lg bg-[#F4730E] text-base font-semibold tracking-[-0.01em] text-white transition-colors hover:bg-[#d9620c] disabled:cursor-default disabled:opacity-80"
                                >
                                    {processing ? (
                                        <>
                                            <span className="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/50 border-t-white" />
                                            Sending…
                                        </>
                                    ) : (
                                        'Get my Monday brief'
                                    )}
                                </button>
                            </form>

                            <p className="mt-[26px] text-[12.5px] font-medium tracking-[0.005em] text-[#76746c]">
                                Free. No spam. Unsubscribe any time.
                            </p>
                        </>
                    )}
                </div>
            </div>
        </>
    );
}
