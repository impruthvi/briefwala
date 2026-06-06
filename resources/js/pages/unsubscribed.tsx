import { Head } from '@inertiajs/react';

export default function Unsubscribed() {
    return (
        <>
            <Head title="Unsubscribed — BriefWala" />
            <div className="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] px-8 py-10">
                <div className="w-full max-w-[360px] text-center">
                    <div className="text-[24px] font-bold leading-none tracking-[-0.02em] text-[#1b1b18]">
                        Brief<span className="text-[#F4730E]">Wala</span>
                    </div>

                    <div className="mx-auto mt-10 flex h-[46px] w-[46px] items-center justify-center rounded-full bg-[#f1efe9]">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12h14" stroke="#76746c" strokeWidth="2.2" strokeLinecap="round" />
                        </svg>
                    </div>

                    <h1 className="mt-6 text-[27px] font-bold leading-tight tracking-[-0.02em] text-[#1b1b18]">
                        You've been unsubscribed.
                    </h1>
                    <p className="mt-[14px] text-[16px] leading-[1.55] text-[#1b1b18]">
                        No more briefs from us. We hope we were useful.
                    </p>
                    <p className="mt-[26px] text-[15px] text-[#76746c]">
                        Change your mind?{' '}
                        <span className="cursor-not-allowed font-semibold opacity-50 text-[#F4730E]">Sign up again</span>{' '}
                        <span className="text-[13px] text-[#a3a097]">(coming soon)</span>
                    </p>
                </div>
            </div>
        </>
    );
}
