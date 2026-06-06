# TODOS

Deferred items from the v1 build. Each has a trigger condition — do not build early.

---

## TODO-1: Sample Brief Preview Email
**What:** Send a hardcoded sample brief immediately after email confirmation (before first real Monday send).
**Why:** Reduces the gap between signup and value delivery. New subscribers wait up to 7 days for their first brief.
**Trigger:** Add after week 2, once real brief quality is validated and you have good example content to use.
**Depends on:** Real brief data (T1b shipped + first real send run).

---

## TODO-2: Week Counter in Email
**What:** Add "This is your Week 3 brief — keep going!" line to weekly email body.
**Why:** Signals progress and builds habit. Psychological commitment device.
**Trigger:** Add after week 4, informed by churn data. Uses `confirmed_at` (already in schema).
**Depends on:** 4 weeks of send data to check if retention holds without it.

---

## TODO-3: WhatsApp Delivery
**What:** Send weekly brief via WhatsApp Business API instead of (or alongside) email.
**Why:** Indian creators check WhatsApp more than email. Open rates 5-10x higher.
**Trigger:** Evaluate after 4 weeks of email open rate data. If open rate < 30%, prioritize this.
**Context:** `whatsapp_number` column already in subscribers schema (E.164). No re-onboarding needed.
**Depends on:** WhatsApp Business API approval (takes 1-2 weeks), Meta partner setup.

---

## TODO-4: Re-subscribe Flow
**What:** Allow unsubscribed users to re-subscribe with the same email.
**Why:** v1 behavior is 422 with "contact us to re-subscribe." This is a dead end for growth.
**Trigger:** When any user actually requests re-subscribe. Implement in v2.
**Context:** Blocked by unique email constraint. Fix: soft-delete pattern or reset `unsubscribed_at` + generate new tokens.
**Depends on:** Nothing technical; just a product decision.

---

## TODO-5: Resend Batch API
**What:** Replace per-subscriber Resend calls with Resend Batch Send API (up to 100 emails per request).
**Why:** At 500+ subscribers, individual API calls take minutes. Batch reduces cron time 10x.
**Trigger:** When subscriber count exceeds 200 OR when brief:send cron takes > 5 minutes.
**Context:** Current architecture: 1 Resend call per subscriber inside loop. Batch API: array of messages per call.
**Depends on:** Nothing — drop-in replacement in SendBriefToSubscriber::send().
