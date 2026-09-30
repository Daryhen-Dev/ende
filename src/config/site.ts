// Single source of truth for business data and navigation.
// Business data is unchanged from the legacy site (confirmed by the owner).
export const site = {
  name: "ENDE Cía. Ltda.",
  shortName: "ENDE",
  tagline: "Ensayos No Destructivos del Ecuador",
  url: "https://www.ende.com.ec",
  locale: "es_EC",
  city: "Quito",
  country: "Ecuador",
} as const;

export type NavItem = { label: string; href: string };

export const nav: NavItem[] = [
  { label: "Inicio", href: "/" },
  { label: "Nosotros", href: "/nosotros" },
  { label: "Servicios", href: "/servicios" },
  { label: "Equipos", href: "/equipos" },
  { label: "Sistemas de gestión", href: "/sistemas-de-gestion" },
  { label: "Contacto", href: "/contacto" },
];
