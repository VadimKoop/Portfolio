"""
design_app.py — a separate file with the “design” (colors/fonts/helpers) for tkinter.

Usage in ui_app.py:
    import design_app as design

    design.apply_window(self)               # for root window
    self.btn_cancel = design.make_button(..., kind="danger")
    self.btn_execute = design.make_button(..., kind="success")

Buttons (exactly 5):
- Old file with signals -> source_file
- New file without signals -> target_file
- Output folder -> output_folder
- Cancel
- Execute
"""

from __future__ import annotations

import tkinter as tk
from dataclasses import dataclass


@dataclass(frozen=True)
class Theme:
    # General background/text
    window_bg: str = "#f6f7fb"
    text_fg: str = "#1f2937"

    # Buttons (default)
    btn_bg: str = "#e5e7eb"
    btn_fg: str = "#111827"
    btn_active_bg: str = "#d1d5db"
    btn_active_fg: str = "#111827"

    # Accent options
    danger_bg: str = "#d32f2f"
    danger_active_bg: str = "#b71c1c"
    success_bg: str = "#2e7d32"
    success_active_bg: str = "#1b5e20"

    # Font (can be changed)
    font: tuple = ("Segoe UI", 10)

THEME = Theme()


def apply_window(root: tk.Tk | tk.Toplevel) -> None:
    """Apply a basic style to a window."""
    root.configure(bg=THEME.window_bg)
    try:
        root.option_add("*Font", THEME.font)
    except Exception:
        pass


def frame(parent: tk.Widget, **kwargs) -> tk.Frame:
    """Frame with theme background."""
    return tk.Frame(parent, bg=THEME.window_bg, **kwargs)


def label(parent: tk.Widget, text: str = "", **kwargs) -> tk.Label:
    """Label with theme background (but allows overriding fg/bg via kwargs))."""
    kwargs.setdefault("bg", THEME.window_bg)
    kwargs.setdefault("fg", THEME.text_fg)
    return tk.Label(parent, text=text, **kwargs)


def make_button(
    parent: tk.Widget,
    text: str,
    command,
    *,
    kind: str = "default",
    width: int | None = None,
    **kwargs,
) -> tk.Button:
    """
    kind:
      - default  (regular)
      - danger   (red, Cancel)
      - success  (green, Execute)
    """
    if kind == "danger":
        bg = THEME.danger_bg
        fg = "white"
        abg = THEME.danger_active_bg
        afg = "white"
    elif kind == "success":
        bg = THEME.success_bg
        fg = "white"
        abg = THEME.success_active_bg
        afg = "white"
    else:
        bg = THEME.btn_bg
        fg = THEME.btn_fg
        abg = THEME.btn_active_bg
        afg = THEME.btn_active_fg

    btn = tk.Button(
        parent,
        text=text,
        command=command,
        bg=bg,
        fg=fg,
        activebackground=abg,
        activeforeground=afg,
        relief="flat",
        bd=0,
        padx=10,
        pady=8,
        **kwargs,
    )
    if width is not None:
        btn.configure(width=width)
    return btn
