# Asherava Project Rules

## Project Scope

- This repository is the `asherava-jaxxon` GeneratePress child theme for Asherava.
- It is not a full WordPress site. Do not add WordPress core, uploads, database dumps, or plugin vendor directories to this repository.
- The live WordPress root is `/sites/asherava.com/files`.
- The live theme path is `/sites/asherava.com/files/wp-content/themes/asherava-jaxxon`.
- The production site is `https://asherava.com`.

## Brand Positioning

- Asherava is a long-term jewelry brand named after Asher and Ava.
- The launch focus is sterling silver rope chains.
- Keep copy calm, direct, premium, and durable.
- Avoid aggressive sale language such as "flash sale", exaggerated discounts, fake urgency, or fake review claims.
- A modest welcome offer such as `10% Welcome Offer` is acceptable.
- Preferred trust language: fair direct pricing, 925 sterling silver, Italian-made or Italian-crafted where accurate, 30-day returns, secure checkout, global shipping where configured.

## Catalog Focus

- Prioritize Rope Chains for launch.
- Current core rope chain widths: `1.8mm`, `3mm`, `4mm`, `4.5mm`, `5.5mm`.
- Current launch length options: `18 inch`, `20 inch`, `22 inch`, `24 inch`, `26 inch`, `28 inch`.
- Use the WooCommerce global attribute `Length` for chain length variations.
- Do not create placeholder live products. Draft products are acceptable when images or final product data are not ready.
- Keep unrelated or future categories out of primary navigation until products are ready.

## Design Rules

- Match the existing Asherava visual system: minimal, black/white, restrained warm neutral accents, generous whitespace, and high-contrast CTAs.
- Typography should feel consistent with the current site. Use existing theme typography tokens before adding new font rules.
- Avoid oversized decorative UI, loud color blocks, or discount-heavy visuals.
- Buttons should be simple, rectangular, high-contrast, and easy to scan.
- Popup design should match the site style: minimal panel, concise offer, clear email field, clear CTA, and unobtrusive dismiss action.
- Product page controls should be practical on mobile and desktop. Quantity controls should stay horizontal.

## Code Rules

- Prefer small, targeted changes in existing theme files.
- Follow existing theme patterns before introducing new abstractions.
- Keep PHP output escaped with the appropriate WordPress escaping functions.
- Do not hard-code fake claims, fake counters, fake review counts, or unverified certifications.
- Do not hide WooCommerce variation controls unless there is a tested replacement that works with real variable products.
- If a plugin owns a feature, avoid competing theme hooks for the same UI.

## Files And Data

- Do not commit `deploy.env`, `data/`, generated ZIP archives, `dist/`, `node_modules/`, or local cache/build artifacts.
- Keep launch scripts in `scripts/` idempotent where possible so reruns do not create duplicate products.
- Do not remove user-created products, pages, or assets unless explicitly asked.

## Git Workflow

- Work on a feature or hotfix branch.
- Commit intentionally with a clear message.
- Push to GitHub and open a pull request into `main`.
- Merge to `main` triggers SpinUpWP sync for the theme.
- If `gh` is available and authenticated, it may be used for PR creation and merge workflow.

## Deployment

- Preferred deployment path: GitHub PR -> merge to `main` -> SpinUpWP auto-sync.
- Direct SSH or rsync is for user-approved urgent fixes only.
- After deployment, flush WordPress cache.
- If frontend still shows old styles or markup, purge SpinUpWP Page Cache.
- Do not SSH into the server or alter server files unless the user explicitly approves that path for the current task.

## Useful Commands

```bash
git status --short --branch
```

```bash
./scripts/deploy-theme.sh
```

```bash
ssh asherava "cd /sites/asherava.com/files && wp cache flush"
```

```bash
ssh asherava "cd /sites/asherava.com/files && wp eval-file wp-content/themes/asherava-jaxxon/scripts/launch-content-seed.php && wp cache flush"
```

```bash
ssh asherava "cd /sites/asherava.com/files && wp eval-file wp-content/themes/asherava-jaxxon/scripts/launch-length-variations.php && wp cache flush"
```

## Verification Checklist

- Check desktop and mobile product pages.
- Confirm Rope Chain navigation links resolve.
- Confirm product variations show on the product page before considering variation work complete.
- Confirm add-to-cart still works after product template changes.
- Confirm popup does not conflict with Omnisend or other email capture tools.
- Confirm cache has been flushed after deployment.
