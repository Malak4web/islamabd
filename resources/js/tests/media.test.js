import { describe, it, expect } from 'vitest'
import {
  projectImage,
  projectGalleryImage,
  matchKey,
  PAGE_BANNERS,
  ABOUT_SECTION_IMAGES,
} from '@/lib/media'

describe('projectImage', () => {
  it('matches the cover to the record category', () => {
    expect(projectImage({ id: 1, category: 'commercial' }).src).toContain('/projects/commercial-')
    expect(projectImage({ id: 1, category: 'administrative' }).src).toContain('/projects/administrative-')
  })

  it('matches Arabic categories too', () => {
    expect(projectImage({ id: 1, category: 'سكني' }).src).toContain('/projects/residential-')
  })

  it('is deterministic, so a filter or reload never reshuffles the grid', () => {
    const a = projectImage({ id: 3, category: 'administrative' })
    const b = projectImage({ id: 3, category: 'administrative' })

    expect(a.src).toBe(b.src)
  })

  it('spreads records in the same category across the pool', () => {
    const covers = [1, 2, 3].map((id) => projectImage({ id, category: 'administrative' }).src)

    expect(new Set(covers).size).toBe(3)
  })

  it('still returns a cover for an unknown category', () => {
    const image = projectImage({ id: 9, category: 'something-else' })

    expect(image.src).toMatch(/\/images\/projects\/.+-1600\.jpg$/)
    expect(image.srcset).toContain('800w')
  })

  it('survives a record with no id or category', () => {
    expect(projectImage({}).src).toBeTruthy()
    expect(projectImage(null).src).toBeTruthy()
  })
})

describe('matchKey', () => {
  it('returns null rather than guessing when nothing matches', () => {
    expect(matchKey('')).toBeNull()
    expect(matchKey(null)).toBeNull()
    expect(matchKey('a category we do not know')).toBeNull()
  })
})

describe('page imagery', () => {
  it('gives every banner both languages of alt text', () => {
    for (const [page, banner] of Object.entries(PAGE_BANNERS)) {
      expect(banner.src, page).toMatch(/\/images\/banners\//)
      expect(banner.alt.en, page).toBeTruthy()
      expect(banner.alt.ar, page).toBeTruthy()
    }
  })

  it('gives each About section a distinct main and detail photograph', () => {
    const sources = Object.values(ABOUT_SECTION_IMAGES).flatMap((i) => [
      i.main.src,
      i.detail.src,
    ])

    // Six sources across three sections, no repeats — the page used to show
    // one frame three times.
    expect(sources).toHaveLength(6)
    expect(new Set(sources).size).toBe(6)
  })

  it('gives every About image both languages of alt text', () => {
    for (const [key, pair] of Object.entries(ABOUT_SECTION_IMAGES)) {
      for (const role of ['main', 'detail']) {
        expect(pair[role].srcset, `${key}.${role}`).toContain('w')
        expect(pair[role].alt.en, `${key}.${role}`).toBeTruthy()
        expect(pair[role].alt.ar, `${key}.${role}`).toBeTruthy()
      }
    }
  })
})

describe('projectGalleryImage', () => {
  const project = { id: 1, category: 'commercial' }

  it('is deterministic for a given slot', () => {
    expect(projectGalleryImage(project, 2).src).toBe(projectGalleryImage(project, 2).src)
  })

  it('does not repeat a frame across a six-image gallery', () => {
    // Every seeded gallery URL is dead, so in practice this IS the gallery.
    // Six identical stand-ins would read as a broken page.
    const sources = Array.from({ length: 6 }, (_, i) => projectGalleryImage(project, i).src)
    expect(new Set(sources).size).toBe(6)
  })

  it('offsets by record so two projects do not show the same set', () => {
    const a = projectGalleryImage({ id: 1 }, 0).src
    const b = projectGalleryImage({ id: 2 }, 0).src
    expect(a).not.toBe(b)
  })

  it('survives a missing record and a junk index', () => {
    expect(projectGalleryImage(null, 0).src).toContain('/images/projects/')
    expect(projectGalleryImage(undefined, undefined).src).toContain('/images/projects/')
    expect(projectGalleryImage({ id: 'x' }, -3).src).toContain('/images/projects/')
  })

  it('ships a srcset so a phone never downloads the 1600px frame', () => {
    expect(projectGalleryImage(project, 0).srcset).toContain('w')
  })
})
