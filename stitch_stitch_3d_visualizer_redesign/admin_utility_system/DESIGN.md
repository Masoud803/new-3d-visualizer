---
name: Admin Utility System
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#434655'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#515f74'
  on-secondary: '#ffffff'
  secondary-container: '#d5e3fc'
  on-secondary-container: '#57657a'
  tertiary: '#943700'
  on-tertiary: '#ffffff'
  tertiary-container: '#bc4800'
  on-tertiary-container: '#ffede6'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#d5e3fc'
  secondary-fixed-dim: '#b9c7df'
  on-secondary-fixed: '#0d1c2e'
  on-secondary-fixed-variant: '#3a485b'
  tertiary-fixed: '#ffdbcd'
  tertiary-fixed-dim: '#ffb596'
  on-tertiary-fixed: '#360f00'
  on-tertiary-fixed-variant: '#7d2d00'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  h1:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.02em
  h2:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
    letterSpacing: -0.01em
  h3:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
  body-base:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  unit: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  container-padding: 24px
  gutter: 16px
---

## Brand & Style
The design system is engineered for high-utility WordPress administrative environments. The brand personality is professional, reliable, and unobtrusive, ensuring that the plugin interface feels like a native yet upgraded extension of the WordPress core. 

The visual style follows a **Corporate / Modern** aesthetic. It prioritizes data density and clarity over decorative flair. By utilizing a "Quiet UI" approach—characterized by expansive white space, thin hairlines, and a restrained color palette—the system reduces cognitive load for users managing complex configurations. The emotional response should be one of efficiency and control.

## Colors
This design system utilizes a high-clarity light mode palette. The primary color is a vivid, trustworthy blue reserved exclusively for primary actions, active states, and progress indicators. 

The neutral palette is expansive, using cool-toned grays to differentiate between background layers and structural components. 
- **Surface**: Pure white (#FFFFFF) for cards and input fields.
- **Background**: Soft gray (#F8FAFC) for the main application canvas to provide subtle contrast against white components.
- **Borders**: Low-contrast slate (#E2E8F0) to define structure without visual noise.
- **Status**: Success (Emerald), Warning (Amber), and Error (Rose) are used sparingly for system feedback.

## Typography
Inter is selected as the sole typeface for its exceptional legibility in tabular data and user interface controls. The hierarchy is strictly enforced through weight and color rather than drastic size changes. 

Large headings use a tighter letter-spacing for a modern feel, while small labels use a slight increase in tracking and uppercase transformation to denote metadata or category headers. Body text defaults to 14px to balance information density with readability, a standard for modern SaaS dashboards.

## Layout & Spacing
The design system employs a **Fluid Grid** model with fixed safe-area margins. It follows an 8pt spacing system (with 4px increments for tight components). 

The sidebar navigation is fixed at 240px width, while the main content area expands to fill the viewport, utilizing a maximum content width of 1440px to prevent excessive line lengths on ultra-wide monitors. Vertical rhythm is maintained through consistent 24px margins between cards and 16px padding within container elements.

## Elevation & Depth
Depth is conveyed through **Tonal Layers** and extremely subtle **Ambient Shadows**. This system avoids heavy blurs to maintain a "flat but layered" professional look.

- **Level 0 (Floor)**: The main background (#F8FAFC).
- **Level 1 (Surface)**: Cards and panels using a 1px solid border (#E2E8F0) and no shadow.
- **Level 2 (Overlay)**: Dropdowns, tooltips, and active modals. These use a refined shadow: `0px 4px 12px rgba(0, 0, 0, 0.05)`.
- **Contrast**: Interactive elements use a change in border color or background tint rather than an increase in shadow depth to signify hover states.

## Shapes
The shape language is **Soft** and disciplined. A standard radius of 6px (0.375rem) is applied to buttons, input fields, and small containers. Larger cards and modals may use up to 8px (0.5rem). This slight rounding softens the technical nature of the dashboard without making it feel overly consumer-focused or "playful," maintaining a crisp, tool-like precision.

## Components
- **Buttons**: Primary buttons use a solid primary blue fill with white text. Secondary buttons use a white fill with a slate border. Padding is 8px vertical by 16px horizontal.
- **Input Fields**: 1px solid border with a 40px default height. Focus states must feature a 2px primary blue ring with an inset white border to ensure accessibility.
- **Cards**: Pure white background, 1px slate border, and 24px internal padding. Card headers should be separated by a subtle 1px divider.
- **Tables**: Row-based layout with 1px horizontal dividers. No vertical lines. Header cells use `label-sm` typography with a light gray background (#F1F5F9).
- **Navigation Sidebar**: High-contrast icons with 14px labels. The active state is indicated by a vertical 3px primary blue bar on the left edge and a subtle tint to the background.
- **Chips/Badges**: Small, semi-rounded (12px radius) tags with low-saturation backgrounds (e.g., light green background with dark green text for "Active" status).