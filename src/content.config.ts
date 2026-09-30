import { defineCollection } from "astro:content";
import { glob } from "astro/loaders";
import { z } from "astro/zod";

// Service pages (NDT techniques). Images use the image() helper so they are
// processed and optimized by astro:assets.
const services = defineCollection({
  loader: glob({ pattern: "**/*.md", base: "./src/content/services" }),
  schema: ({ image }) =>
    z.object({
      title: z.string(),
      shortTitle: z.string(),
      acronym: z.string().optional(),
      order: z.number(),
      // Card text and page meta description (1–2 sentences).
      summary: z.string().max(160),
      cover: image(),
      gallery: z
        .array(
          z.object({
            src: image(),
            alt: z.string(),
          }),
        )
        .optional(),
      // Short technique/application bullets shown in the detail sidebar.
      highlights: z.array(z.string()),
      // Standards and codes the service is performed against (API, ASME...).
      standards: z.array(z.string()).optional(),
    }),
});

export const collections = { services };
