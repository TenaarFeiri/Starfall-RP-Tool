# Titler Continuity Design Notes (4 Linked Objects)

The in-game titler can be implemented as a formatter + segmenter pipeline:

1. Build canonical text block (name/tags/description/etc.).
2. Normalize newline accounting where `\\n` counts as two characters.
3. Segment into up to four chunks (max 255 chars each).
4. Apply chunk-to-prim mapping in fixed order for continuity.
5. On content updates, fully recompute all chunks and reapply all four `llSetText()` values atomically from script perspective.

This avoids drift and ensures changes in the top segment correctly shift lower segments.

The current framework delivers the backend data and sync channels needed for this next implementation stage.
