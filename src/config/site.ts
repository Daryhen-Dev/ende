// Single source of truth for business data and navigation.
// Business data is unchanged from the legacy site (confirmed by the owner).
// See docs/legacy-content/business-data.md for the verbatim source.
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

// Internal hrefs end with a trailing slash: the site builds with
// `trailingSlash: "always"` and `build.format: "directory"`, so /x/ is the
// canonical form (avoids a mod_dir 301 on Apache).
export const nav: NavItem[] = [
  { label: "Inicio", href: "/" },
  { label: "Nosotros", href: "/nosotros/" },
  { label: "Servicios", href: "/servicios/" },
  { label: "Equipos", href: "/equipos/" },
  { label: "Sistemas de gestión", href: "/sistemas-de-gestion/" },
  { label: "Contacto", href: "/contacto/" },
];

export type Phone = { label: string; tel: string };

export type ContactInfo = {
  addressLines: string[];
  city: string;
  country: string;
  phones: Phone[];
  complaintsPhone: Phone;
  email: string;
  facebook: string;
};

export const contact: ContactInfo = {
  addressLines: [
    "Av. Amazonas N26-179 y Av. Orellana",
    "Edificio Torrealba, PH (piso 11), Oficina 1101",
  ],
  city: "Quito",
  country: "Ecuador",
  phones: [
    { label: "02 252 9713", tel: "+59322529713" },
    { label: "02 252 6229", tel: "+59322526229" },
    { label: "099 252 5570", tel: "+593992525570" },
  ],
  complaintsPhone: { label: "099 252 7759", tel: "+593992527759" },
  email: "ende@ende.com.ec",
  facebook: "https://www.facebook.com/ENDE-CIA-LTDA-209989702696176/?fref=ts",
};

export type Certification = { name: string; detail: string };

export const certifications: Certification[] = [
  { name: "ISO 9001:2015", detail: "Sistema de gestión de calidad" },
  {
    name: "ISO/IEC 17020:2013",
    detail:
      "Organismo de inspección acreditado ante el SAE, Resolución No. SAE-ACR-0376-2025",
  },
];

export type Membership = { name: string; url: string };

export const memberships: Membership[] = [
  { name: "ASME", url: "https://www.asme.org" },
  { name: "ASNT", url: "https://www.asnt.org" },
  { name: "AWS", url: "https://www.aws.org" },
  { name: "API", url: "https://www.api.org" },
];
