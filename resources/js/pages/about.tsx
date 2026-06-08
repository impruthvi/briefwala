import { Head, Link } from '@inertiajs/react';

function Logo() {
    return (
        <div className="text-[24px] font-bold leading-none tracking-[-0.02em] text-[#1b1b18]">
            Brief<span className="text-[#F4730E]">Wala</span>
        </div>
    );
}

function StepNumber({ n }: { n: number }) {
    return (
        <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#F4730E]/10 text-[14px] font-bold text-[#F4730E]">
            {n}
        </div>
    );
}

function CheckIcon() {
    return (
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" className="mt-0.5 shrink-0">
            <circle cx="9" cy="9" r="8.5" stroke="#F4730E" strokeOpacity="0.3" />
            <path d="M5.5 9l2.5 2.5 4.5-5" stroke="#F4730E" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
    );
}

export default function About() {
    return (
        <>
            <Head title="About — BriefWala" />
            <div className="min-h-screen bg-[#FDFDFC]">
                {/* Nav */}
                <nav className="mx-auto flex max-w-[680px] items-center justify-between px-8 pt-8 pb-6">
                    <Link href="/">
                        <Logo />
                    </Link>
                    <Link
                        href="/"
                        className="h-[38px] rounded-lg bg-[#F4730E] px-4 text-[14px] font-semibold text-white transition-colors hover:bg-[#d9620c] inline-flex items-center"
                    >
                        Subscribe →
                    </Link>
                </nav>

                <main className="mx-auto max-w-[680px] px-8 pb-20">
                    {/* Hero */}
                    <section className="mt-10 mb-14">
                        <h1 className="text-[38px] font-bold leading-[1.15] tracking-[-0.025em] text-[#1b1b18] text-pretty">
                            A week of content ideas,{' '}
                            <span className="text-[#76746c]">researched while you sleep.</span>
                        </h1>
                        <p className="mt-5 text-[18px] leading-[1.6] text-[#1b1b18]">
                            BriefWala is a free weekly email for Indian creators. Every Monday morning you get five
                            ready-to-shoot content angles — trending topics, turned into hooks, in your language.
                        </p>
                    </section>

                    {/* Divider */}
                    <div className="mb-14 h-px bg-[#e4e2db]" />

                    {/* How it works */}
                    <section className="mb-14">
                        <h2 className="mb-8 text-[13px] font-semibold uppercase tracking-[0.08em] text-[#76746c]">
                            How it works
                        </h2>
                        <div className="space-y-7">
                            <div className="flex gap-4">
                                <StepNumber n={1} />
                                <div>
                                    <div className="text-[17px] font-semibold text-[#1b1b18]">Tell us your beat</div>
                                    <p className="mt-1.5 text-[15.5px] leading-[1.55] text-[#76746c]">
                                        Your niche, platform, and language — one short form, about 30 seconds.
                                    </p>
                                </div>
                            </div>
                            <div className="flex gap-4">
                                <StepNumber n={2} />
                                <div>
                                    <div className="text-[17px] font-semibold text-[#1b1b18]">We research every week</div>
                                    <p className="mt-1.5 text-[15.5px] leading-[1.55] text-[#76746c]">
                                        Our AI scans Google Trends and YouTube for what's breaking in India, inside your
                                        niche.
                                    </p>
                                </div>
                            </div>
                            <div className="flex gap-4">
                                <StepNumber n={3} />
                                <div>
                                    <div className="text-[17px] font-semibold text-[#1b1b18]">Monday, your brief lands</div>
                                    <p className="mt-1.5 text-[15.5px] leading-[1.55] text-[#76746c]">
                                        Five content angles with hooks you can use as a title or Reel opener — word for
                                        word.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* Divider */}
                    <div className="mb-14 h-px bg-[#e4e2db]" />

                    {/* What's inside */}
                    <section className="mb-14">
                        <h2 className="mb-7 text-[13px] font-semibold uppercase tracking-[0.08em] text-[#76746c]">
                            What's inside each brief
                        </h2>
                        <div className="space-y-4">
                            <div className="flex gap-3">
                                <CheckIcon />
                                <p className="text-[16px] leading-[1.55] text-[#1b1b18]">
                                    5 trending topics matched to your niche this week
                                </p>
                            </div>
                            <div className="flex gap-3">
                                <CheckIcon />
                                <p className="text-[16px] leading-[1.55] text-[#1b1b18]">
                                    A ready-to-use hook for each — in Hindi, English, Hinglish, Tamil or Telugu
                                </p>
                            </div>
                            <div className="flex gap-3">
                                <CheckIcon />
                                <p className="text-[16px] leading-[1.55] text-[#1b1b18]">
                                    One line on why it's trending, so you can move first
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* Divider */}
                    <div className="mb-14 h-px bg-[#e4e2db]" />

                    {/* Who it's for */}
                    <section className="mb-14">
                        <h2 className="mb-5 text-[13px] font-semibold uppercase tracking-[0.08em] text-[#76746c]">
                            Who it's for
                        </h2>
                        <p className="text-[17px] leading-[1.65] text-[#1b1b18]">
                            YouTubers, Instagram Reels creators, and short-form storytellers across India who'd rather
                            create than spend Sunday night hunting for the next idea.
                        </p>
                    </section>

                    {/* CTA */}
                    <section className="rounded-2xl bg-[#F4730E]/8 px-8 py-9">
                        <p className="text-[22px] font-bold tracking-[-0.02em] text-[#1b1b18]">It's free.</p>
                        <p className="mt-2 text-[15.5px] text-[#76746c]">No spam, unsubscribe any time.</p>
                        <Link
                            href="/"
                            className="mt-6 inline-flex h-[50px] items-center justify-center rounded-lg bg-[#F4730E] px-6 text-[15.5px] font-semibold tracking-[-0.01em] text-white transition-colors hover:bg-[#d9620c]"
                        >
                            Get my Monday brief
                        </Link>
                    </section>
                </main>
            </div>
        </>
    );
}
