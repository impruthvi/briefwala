import { Head, Link } from '@inertiajs/react';

export default function Confirmed() {
    return (
        <>
            <Head title="You're in! — BriefWala" />
            <div className="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] px-8 py-10">
                <div className="w-full max-w-[360px] text-center">
                    <div className="text-[24px] font-bold leading-none tracking-[-0.02em] text-[#1b1b18]">
                        Brief<span className="text-[#F4730E]">Wala</span>
                    </div>

                    <div className="mt-10 text-[44px] leading-none">🎉</div>

                    <h1 className="mt-[22px] text-[30px] font-bold leading-tight tracking-[-0.02em] text-[#1b1b18]">
                        You're in!
                    </h1>
                    <p className="mt-4 text-[17px] font-medium leading-[1.55] text-[#1b1b18]">
                        Your first brief arrives Monday morning.
                    </p>
                    <p className="mt-2.5 text-[15px] leading-[1.55] text-[#76746c]">
                        No action needed — just check your inbox on Monday.
                    </p>

                    <Link
                        href="/"
                        className="mt-8 inline-flex items-center gap-1.5 text-[15px] font-semibold text-[#F4730E] hover:text-[#d9620c]"
                    >
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path
                                d="M10 3.5L5.5 8l4.5 4.5"
                                stroke="currentColor"
                                strokeWidth="1.8"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                        Back to BriefWala
                    </Link>
                </div>
            </div>
        </>
    );
}
