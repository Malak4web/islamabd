/**
 * Icon geometry for the public site — see EaiIcon.vue for the drawing rules.
 * Kept in a plain module (not inside <script setup>) so the service-icon name
 * list can be imported by mappers and tests.
 */

/* ---------------------------------------------------------------------------
 * Service marks — small architectural drawings, one per service line.
 * Each is a recognisable plan or section, not a generic pictogram.
 * ------------------------------------------------------------------------ */
export const SERVICE = {
  // Office floor plan: partitions and a door swing.
  administrative: [
    'M3 4.5h18v15H3z',
    'M14 4.5v15',
    'M3 12.5h11',
    'M7.5 12.5a4 4 0 0 1 4 4',
  ],
  // Multi-storey commercial block, elevation, with entrance bay.
  commercial: [
    'M4 20.5V6h16v14.5',
    'M4 11h16',
    'M4 15.5h16',
    'M10.5 20.5v-5h3v5',
    'M2 20.5h20',
  ],
  // House section: pitched roof, party walls, doorway.
  residential: [
    'M3 10.75 12 3.5l9 7.25',
    'M5.5 12.75v7.75',
    'M18.5 12.75v7.75',
    'M10 20.5v-5h4v5',
    'M2 20.5h20',
  ],
  // Facade study: cladding coursing and a vertical joint, specimen tree at grade.
  // The tree is what separates this from a window — keep it a solid canopy,
  // not stroke ticks, or the mark collapses into `commercial` at small sizes.
  exterior: [
    'M9.5 4.5h11v16h-11z',
    'M9.5 10h11',
    'M9.5 15.25h11',
    'M15 4.5v16',
    'M2 20.5h20',
    'M5.25 20.5v-4.5',
    'M5.25 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6z',
  ],
  // Guest bed, elevation: headboard, mattress, pillow, legs.
  hospitality: [
    'M2.5 18.5h19',
    'M2.5 18.5v-5h19v5',
    'M2.5 13.5V8',
    'M21.5 13.5v-2',
    'M6 13.5v-2h5v2',
    'M4 20.5v-2',
    'M20 20.5v-2',
  ],
  // Garden elevation: specimen tree with a branch, massed planting at grade.
  // Shrubs are domes rather than vertical ticks — ticks vanish below 24px.
  landscape: [
    'M2 20.5h20',
    'M15 20.5v-6',
    'M15 14.5a4 4 0 1 0 0-8 4 4 0 0 0 0 8z',
    'M15 11.75 12.5 9.25',
    'M4 20.5a2.5 2.5 0 0 1 5 0',
    'M9.5 20.5a1.75 1.75 0 0 1 3.5 0',
  ],
  // Display casework: shelving runs with merchandise blocks.
  retail: [
    'M3.5 4.5h17v16h-17z',
    'M3.5 10h17',
    'M3.5 15.25h17',
    'M6.5 7h2.5v3H6.5z',
    'M13.5 12.25h3.5v3h-3.5z',
    'M6.5 17.5h3.5',
  ],
  // Sawtooth north-light roof — the canonical industrial section.
  industrial: [
    'M2.5 12.5 7 7.5v5l4.5-5v5l4.5-5v5l4.5-5v5',
    'M2.5 12.5v8',
    'M20.5 12.5v8',
    'M2 20.5h20',
    'M8.5 20.5v-4h4v4',
  ],
}

/* ---------------------------------------------------------------------------
 * Interface marks.
 * ------------------------------------------------------------------------ */
export const UI = {
  'arrow-right': ['M3.5 12h16', 'M13 5.5 19.5 12 13 18.5'],
  'arrow-left': ['M20.5 12h-16', 'M11 5.5 4.5 12 11 18.5'],
  'arrow-up': ['M12 20.5v-16', 'M5.5 11 12 4.5 18.5 11'],
  'arrow-down': ['M12 3.5v16', 'M5.5 13 12 19.5 18.5 13'],
  'chevron-down': ['M5 9 12 16l7-7'],
  'chevron-up': ['M5 15l7-7 7 7'],
  'chevron-right': ['M9 4.5 16.5 12 9 19.5'],
  'chevron-left': ['M15 4.5 7.5 12 15 19.5'],
  close: ['M4.5 4.5 19.5 19.5', 'M19.5 4.5 4.5 19.5'],
  check: ['M3.5 12.5 9 18 20.5 6.5'],
  plus: ['M12 4v16', 'M4 12h16'],
  minus: ['M4 12h16'],
  menu: ['M3 6.5h18', 'M3 12h18', 'M3 17.5h18'],
  search: ['M10.5 17a6.5 6.5 0 1 0 0-13 6.5 6.5 0 0 0 0 13z', 'M15.25 15.25 20.5 20.5'],
  expand: ['M4.5 9.5v-5h5', 'M19.5 14.5v5h-5', 'M4.5 4.5 10 10', 'M19.5 19.5 14 14'],
  mail: ['M2.5 5.5h19v13h-19z', 'M2.5 6.25 12 13l9.5-6.75'],
  phone: [
    'M7.5 3.5h-3a1 1 0 0 0-1 1c0 8.837 7.163 16 16 16a1 1 0 0 0 1-1v-3l-4.5-1.5-2 2.5a15 15 0 0 1-6.5-6.5l2.5-2z',
  ],
  pin: [
    'M12 21.5c4-4.4 6.5-7.9 6.5-11.5a6.5 6.5 0 1 0-13 0c0 3.6 2.5 7.1 6.5 11.5z',
    'M12 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z',
  ],
  clock: ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z', 'M12 6.75V12l3.5 2.5'],
  image: [
    'M3 5.5h18v13H3z',
    'M3 15l5-4.5 4 3.5 3.5-3 5.5 5',
    'M8.75 10.25a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5z',
  ],
  // Fallback service mark: a simple building elevation.
  building: ['M5 20.5V4.5h14v16', 'M2.5 20.5h19', 'M8.5 8h3', 'M15 8h1.5', 'M8.5 12h3', 'M15 12h1.5', 'M10 20.5v-4.5h4v4.5'],

  /* ---------------------------------------------------------------------
   * Navigation marks — drawn for the phone tab bar.
   *
   * A tab bar glyph is read at 22px, in the corner of the eye, while the
   * thumb is already moving. So these are the simplest possible statement of
   * each destination, and each one keeps the ground line the rest of the set
   * uses: on this site a mark sits on a datum, it does not float.
   * ------------------------------------------------------------------ */

  // Home: a gabled elevation on its ground line.
  home: ['M3 10.75 12 3.5l9 7.25', 'M5.5 9.5v11', 'M18.5 9.5v11', 'M2.5 20.5h19', 'M9.75 20.5v-5.5h4.5v5.5'],

  // Services: a set of sheets, seen in section — the practice's lines of work
  // are literally a stack of drawings.
  layers: ['M12 3.5 3 8l9 4.5L21 8z', 'M3 12.25 12 16.75l9-4.5', 'M3 16.5 12 21l9-4.5'],

  // Projects: a setting-out grid. It is the sheet the work is drawn on, and
  // at this size it reads as a portfolio of frames.
  grid: ['M3.5 3.5h17v17h-17z', 'M3.5 9.17h17', 'M3.5 14.83h17', 'M9.17 3.5v17', 'M14.83 3.5v17'],

  // About: a pair of dividers, open on the sheet, with the arc they strike.
  compass: ['M12 6.5 6.5 20.5', 'M12 6.5 17.5 20.5', 'M12 3.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z', 'M8.6 15.6a7.4 7.4 0 0 0 6.8 0'],
}

/* ---------------------------------------------------------------------------
 * Brand marks. Filled, real geometry — a trademark restyled is a trademark
 * misused, and users identify these by silhouette.
 * ------------------------------------------------------------------------ */
export const BRAND = {
  instagram: [
    'M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.8 3.8 0 0 1-1.38-.9 3.8 3.8 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16zm0 1.98c-3.15 0-3.5.01-4.74.07-1.14.05-1.76.24-2.17.4-.55.21-.94.47-1.35.88-.41.41-.67.8-.88 1.35-.16.41-.35 1.03-.4 2.17-.06 1.24-.07 1.59-.07 4.74s.01 3.5.07 4.74c.05 1.14.24 1.76.4 2.17.21.55.47.94.88 1.35.41.41.8.67 1.35.88.41.16 1.03.35 2.17.4 1.24.06 1.59.07 4.74.07s3.5-.01 4.74-.07c1.14-.05 1.76-.24 2.17-.4.55-.21.94-.47 1.35-.88.41-.41.67-.8.88-1.35.16-.41.35-1.03.4-2.17.06-1.24.07-1.59.07-4.74s-.01-3.5-.07-4.74c-.05-1.14-.24-1.76-.4-2.17a3.6 3.6 0 0 0-.88-1.35 3.6 3.6 0 0 0-1.35-.88c-.41-.16-1.03-.35-2.17-.4-1.24-.06-1.59-.07-4.74-.07z',
    'M12 6.87a5.13 5.13 0 1 0 0 10.26 5.13 5.13 0 0 0 0-10.26zm0 8.46a3.33 3.33 0 1 1 0-6.66 3.33 3.33 0 0 1 0 6.66z',
    'M18.54 6.67a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z',
  ],
  facebook: [
    'M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.91 3.77-3.91 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.78-1.63 1.57v1.89h2.78l-.44 2.9h-2.34V22c4.78-.76 8.44-4.92 8.44-9.94z',
  ],
  linkedin: [
    'M6.94 8.4H3.56V21h3.38V8.4zM5.25 2.9a1.96 1.96 0 1 0 0 3.92 1.96 1.96 0 0 0 0-3.92zM20.44 13.6c0-3.24-1.73-4.75-4.04-4.75-1.86 0-2.7 1.03-3.16 1.75V8.4H9.86c.04.95 0 12.6 0 12.6h3.38v-7.04c0-.3.02-.6.11-.82.25-.6.8-1.23 1.73-1.23 1.22 0 1.71.93 1.71 2.3V21h3.38l.27-7.4z',
  ],
  twitter: [
    'M17.53 3h3.11l-6.8 7.77L21.85 21h-6.26l-4.9-6.41L4.08 21H.96l7.27-8.31L.42 3h6.42l4.43 5.86L17.53 3zm-1.09 16.14h1.72L6.7 4.77H4.86l11.58 14.37z',
  ],
  whatsapp: [
    'M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.65.08-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.14-.14.3-.35.45-.53.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.67-1.62-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.01-1.04 2.47s1.06 2.87 1.21 3.07c.15.2 2.1 3.2 5.08 4.49.71.3 1.26.49 1.7.63.71.23 1.36.19 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35z',
    'M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.86 9.86 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.25-8.23a8.24 8.24 0 0 1 8.23 8.24c0 4.54-3.7 8.23-8.23 8.23z',
  ],
}

/** Every icon name the system knows, for validation at the call site. */
export const ICONS = { ...SERVICE, ...UI, ...BRAND }
export const SERVICE_ICON_NAMES = Object.keys(SERVICE)
export const BRAND_ICON_NAMES = Object.keys(BRAND)
