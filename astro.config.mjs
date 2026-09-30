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
  trailingSlash: "ignore",
  vite: {
    plugins: [tailwindcss()],
  },
  integrations: [sitemap()],
});
