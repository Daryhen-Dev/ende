// @ts-check
import { defineConfig } from "astro/config";

import tailwindcss from "@tailwindcss/vite";
import sitemap from "@astrojs/sitemap";

// https://astro.build/config
export default defineConfig({
  site: "https://www.ende.com.ec",
  // Static output served from the mysitearea.com file manager.
  output: "static",
  // Emit /ruta/index.html so clean URLs work on any Apache/nginx host.
  build: { format: "directory" },
  // Canonical URLs and every internal href use trailing slashes; Apache's
  // mod_dir would otherwise 301 /equipos to /equipos/ on every request.
  trailingSlash: "always",
  vite: {
    plugins: [tailwindcss()],
    // Expose PUBLIC_* client-side env vars to import.meta.env.
    envPrefix: ["PUBLIC_"],
  },
  integrations: [
    sitemap({
      // The 404 page is noindex and must not appear in the sitemap.
      filter: (page) => !page.includes("404"),
    }),
  ],
});
