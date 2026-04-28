---
name: Atmospheric Precision
colors:
  surface: '#0b1326'
  surface-dim: '#0b1326'
  surface-bright: '#31394d'
  surface-container-lowest: '#060e20'
  surface-container-low: '#131b2e'
  surface-container: '#171f33'
  surface-container-high: '#222a3d'
  surface-container-highest: '#2d3449'
  on-surface: '#dae2fd'
  on-surface-variant: '#c1c7d2'
  inverse-surface: '#dae2fd'
  inverse-on-surface: '#283044'
  outline: '#8b919b'
  outline-variant: '#414750'
  surface-tint: '#9ccaff'
  primary: '#9ccaff'
  on-primary: '#003256'
  primary-container: '#2271b1'
  on-primary-container: '#e9f1ff'
  inverse-primary: '#0062a1'
  secondary: '#c6c6c7'
  on-secondary: '#2f3131'
  secondary-container: '#454747'
  on-secondary-container: '#b4b5b5'
  tertiary: '#ffb86e'
  on-tertiary: '#492900'
  tertiary-container: '#9f5e00'
  on-tertiary-container: '#ffeee0'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#d0e4ff'
  primary-fixed-dim: '#9ccaff'
  on-primary-fixed: '#001d35'
  on-primary-fixed-variant: '#00497a'
  secondary-fixed: '#e2e2e2'
  secondary-fixed-dim: '#c6c6c7'
  on-secondary-fixed: '#1a1c1c'
  on-secondary-fixed-variant: '#454747'
  tertiary-fixed: '#ffdcbd'
  tertiary-fixed-dim: '#ffb86e'
  on-tertiary-fixed: '#2c1600'
  on-tertiary-fixed-variant: '#693c00'
  background: '#0b1326'
  on-background: '#dae2fd'
  surface-variant: '#2d3449'
typography:
  header-caps:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.1em
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 14px
  value-mono:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 14px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 4px
  container-padding: 16px
  element-gap: 8px
  toolbar-height: 40px
  panel-width: 280px
---

## Brand & Style

This design system is built for a premium 3D Product Visualizer where the interface must recede to allow the subject matter—the 3D model—to remain the focal point. The style is a sophisticated blend of **Minimalism** and **Glassmorphism**, emphasizing clarity, depth, and technical precision.

The emotional response is one of high-end professionalism and "airiness." By utilizing frosted glass surfaces and ultra-clean typography, the UI feels lightweight and modern, reminiscent of high-end photographic or industrial design tools. The goal is to provide a space-efficient cockpit that feels expansive rather than cluttered.

## Colors

The palette is primarily a **neutral dark scheme** to maximize the contrast of 3D renders and lighting. 

- **Primary Accent (#2271b1):** Used sparingly for active states, primary actions, and selection highlights. 
- **Glass Surfaces:** The system relies on two translucent fills. A dark tinted glass for main panels to maintain legibility, and a lighter, more transparent frost for floating toolbars.
- **Accents:** High-contrast white is reserved for critical text and icons, while muted grays are used for secondary information to reduce visual noise.

## Typography

This design system utilizes **Inter** exclusively for its utilitarian, neutral profile. 

- **Headers:** Navigation and section titles use small, uppercase labels with increased letter-spacing to create a technical, "blueprint" aesthetic without occupying much vertical space.
- **Data Entry:** For numerical values in the 3D visualizer (coordinates, scale, rotation), tabular figures are used to ensure vertical alignment in lists.
- **Readability:** Body text is kept at a functional 14px to balance space-efficiency with legibility in a professional tool environment.

## Layout & Spacing

The layout follows a **No Grid** philosophy, prioritizing a "HUD" (Heads-Up Display) experience. Controls are organized into floating, docked panels and pill-shaped toolbars that sit atop the 3D viewport.

- **Space Efficiency:** A tight 4px baseline grid is used. Elements are packed closely with 8px gaps to maximize the visible area of the 3D scene.
- **Floating Panels:** Sidebars are capped at a fixed 280px width to ensure they don't overwhelm smaller laptop screens.
- **Margins:** High-level containers maintain a 16px safe area from the viewport edges.

## Elevation & Depth

Hierarchy is established through **Backdrop Blurs** and layered transparency rather than heavy shadows.

- **Base Layer:** The 3D viewport.
- **Middle Layer (Panels):** Semi-transparent dark glass (`rgba(15, 23, 42, 0.6)`) with a `20px` backdrop blur. These use a subtle 1px inner border (white at 10% opacity) to simulate a glass edge.
- **Top Layer (Modals/Popovers):** Higher opacity glass with a layered, diffused shadow (0px 10px 30px rgba(0,0,0,0.3)) to pull the element forward.
- **Interaction:** Hovering over elements increases the background opacity slightly to provide tactile feedback without color shifts.

## Shapes

The shape language balances structural rigor with soft modernism.

- **Containers:** Main panels and large flyouts use a **12px radius**, creating a professional frame.
- **UI Elements:** Buttons, input fields, and dropdowns use a **6px radius** to feel precise and technical.
- **Toolbars & Swatches:** Primary floating toolbars and material swatches are strictly **Pill-shaped or Circular**, distinguishing them as "active tools" versus "informational panels."

## Components

- **Pill-Shaped Toolbars:** Horizontal containers with a high backdrop blur. Icons within are monochrome, turning to the Primary Blue when active.
- **Circular Swatches:** Material and color selectors must be circular with a 2px white border for the "selected" state, plus a subtle drop shadow to pop against the 3D background.
- **Input Fields:** Minimalist design with only a bottom border or a very faint translucent background. Labels sit above in the `header-caps` style.
- **Glass Buttons:** Primary buttons use the branding blue with 80% opacity; secondary buttons use a white-glass frost with no border.
- **Property Lists:** Compact rows with a label on the left and a value/input on the right, separated by a subtle 1px divider to maintain a clean, table-like structure for 3D attributes.
- **Segmented Controls:** Pill-shaped toggles used for switching between View modes (Wireframe, Lit, Texture), using a sliding glass background to indicate the active state.