// Generates public/og-default.jpg (1200x630) used as the default Open Graph /
// Twitter card image. Brand gradient + logo on a white rounded card + tagline.
// Run once with: node scripts/generate-og.mjs
import sharp from "sharp";
import { fileURLToPath } from "node:url";
import path from "node:path";

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), "..");
const logoPath = path.join(root, "src/assets/brand/logo-ende.png");
const outPath = path.join(root, "public/og-default.jpg");

const WIDTH = 1200;
const HEIGHT = 630;

// Brand tokens from src/styles/global.css.
const BRAND_700 = "#0071BC";
const BRAND_900 = "#2E3192";

const logo = sharp(logoPath);
const { width: logoW, height: logoH } = await logo.metadata();

// White rounded card (left) holds the logo; the tagline sits to the right.
const CARD = 460;
const CARD_X = 90;
const CARD_Y = (HEIGHT - CARD) / 2;
// Fit the logo inside the card with comfortable padding, preserving aspect.
const LOGO_BOX = 340;
const logoScale = Math.min(LOGO_BOX / logoW, LOGO_BOX / logoH);
const logoResized = await logo
  .resize(Math.round(logoW * logoScale), Math.round(logoH * logoScale))
  .png()
  .toBuffer({ resolveWithObject: true });

const background = Buffer.from(`
<svg width="${WIDTH}" height="${HEIGHT}" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="brand" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="${BRAND_700}" />
      <stop offset="100%" stop-color="${BRAND_900}" />
    </linearGradient>
  </defs>
  <rect width="${WIDTH}" height="${HEIGHT}" fill="url(#brand)" />
  <rect
    x="${CARD_X}" y="${CARD_Y}" width="${CARD}" height="${CARD}"
    rx="48" ry="48" fill="#ffffff"
  />
</svg>
`);

const taglineX = CARD_X + CARD + 70;
const overlay = Buffer.from(`
<svg width="${WIDTH}" height="${HEIGHT}" xmlns="http://www.w3.org/2000/svg">
  <text
    x="${taglineX}" y="278"
    font-family="Arial, 'Segoe UI', 'Helvetica Neue', sans-serif"
    font-size="42" font-weight="700" fill="#ffffff"
  >Ensayos No Destructivos</text>
  <text
    x="${taglineX}" y="338"
    font-family="Arial, 'Segoe UI', 'Helvetica Neue', sans-serif"
    font-size="42" font-weight="700" fill="#ffffff"
  >del Ecuador</text>
  <text
    x="${taglineX}" y="398"
    font-family="Arial, 'Segoe UI', 'Helvetica Neue', sans-serif"
    font-size="26" fill="#aee1f6"
  >ende.com.ec</text>
</svg>
`);

await sharp(background)
  .composite([
    {
      input: logoResized.data,
      left: Math.round(CARD_X + (CARD - logoResized.info.width) / 2),
      top: Math.round(CARD_Y + (CARD - logoResized.info.height) / 2),
    },
    { input: overlay, left: 0, top: 0 },
  ])
  .jpeg({ quality: 88, mozjpeg: true })
  .toFile(outPath);

console.log(`OG image written to ${path.relative(root, outPath)}`);
