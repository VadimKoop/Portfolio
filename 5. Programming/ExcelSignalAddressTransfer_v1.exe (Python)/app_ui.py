"""
app_ui.py — GUI (tkinter) to select files and run main.run_transfer().

Buttons (exactly 5):
- Old file with signals -> source_file
- New file without signals -> target_file
- Output folder -> output_folder
- Cancel
- Execute
"""

from __future__ import annotations

import os
import threading
import tkinter as tk
from tkinter import filedialog, messagebox

import app_design as design
import main


class App(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title("Excel Obj. Addr Transfer")
        self.resizable(False, False)
        design.apply_window(self)

        self.source_path = tk.StringVar(value="")
        self.target_path = tk.StringVar(value="")
        self.output_path = tk.StringVar(value="")
        self.status = tk.StringVar(value="Select files and click Execute.")

        self._build_ui()
        self._update_execute_state()

    def _build_ui(self):
        pad = {"padx": 12, "pady": 6}

        # 5 buttons
        btn_frame = design.frame(self)
        btn_frame.pack(fill="x", **pad)

        self.btn_source = design.make_button(btn_frame, "Old file with signals", self.pick_source, kind="default", width=18)
        self.btn_source.grid(row=0, column=0, padx=6, pady=6)

        self.btn_target = design.make_button(btn_frame, "New file without signals", self.pick_target, kind="default", width=18)
        self.btn_target.grid(row=0, column=1, padx=6, pady=6)

        self.btn_output = design.make_button(btn_frame, "Output folder", self.pick_output, kind="default", width=18)
        self.btn_output.grid(row=0, column=2, padx=6, pady=6)

        self.btn_cancel = design.make_button(btn_frame, "Cancel", self.destroy, kind="danger", width=18)
        self.btn_cancel.grid(row=1, column=0, padx=6, pady=6)

        self.btn_execute = design.make_button(btn_frame, "Execute", self.execute, kind="success", width=38)
        self.btn_execute.grid(row=1, column=1, columnspan=2, padx=6, pady=6, sticky="ew")

        # Paths preview (labels)
        info = design.frame(self)
        info.pack(fill="x", **pad)

        design.label(info, text="Old file with signals path:").grid(row=0, column=0, sticky="w")
        design.label(info, textvariable=self.source_path, wraplength=560, justify="left").grid(row=0, column=1, sticky="w")

        design.label(info, text="New file without signals path:").grid(row=1, column=0, sticky="w")
        design.label(info, textvariable=self.target_path, wraplength=560, justify="left").grid(row=1, column=1, sticky="w")

        design.label(info, text="Output folder path:").grid(row=2, column=0, sticky="w")
        design.label(info, textvariable=self.output_path, wraplength=560, justify="left").grid(row=2, column=1, sticky="w")

        # Status
        status_frame = design.frame(self)
        status_frame.pack(fill="x", **pad)
        design.label(status_frame, textvariable=self.status, fg="gray25", wraplength=650, justify="left").pack(anchor="w")

    def pick_source(self):
        path = filedialog.askopenfilename(
            title="Select SOURCE Excel file",
            filetypes=[("Excel files", "*.xlsx"), ("All files", "*.*")],
        )
        if path:
            self.source_path.set(path)
            self._update_execute_state()

    def pick_target(self):
        path = filedialog.askopenfilename(
            title="Select TARGET Excel file",
            filetypes=[("Excel files", "*.xlsx"), ("All files", "*.*")],
        )
        if path:
            self.target_path.set(path)
            self._update_execute_state()

    def pick_output(self):
        folder = filedialog.askdirectory(title="Select OUTPUT folder")
        if folder:
            self.output_path.set(folder)
            self._update_execute_state()

    def _set_busy(self, busy: bool):
        state = "disabled" if busy else "normal"
        self.btn_source.config(state=state)
        self.btn_target.config(state=state)
        self.btn_output.config(state=state)
        self.btn_cancel.config(state=state)
        self.btn_execute.config(state="disabled" if busy else ("normal" if self._can_execute() else "disabled"))

    def _can_execute(self) -> bool:
        return bool(self.source_path.get().strip() and self.target_path.get().strip() and self.output_path.get().strip())

    def _update_execute_state(self):
        self.btn_execute.config(state="normal" if self._can_execute() else "disabled")

    def execute(self):
        if not self._can_execute():
            messagebox.showwarning("Missing data", "Select Old file with signals, New file without signals and Output folder.")
            return

        src = self.source_path.get().strip()
        dst = self.target_path.get().strip()
        out_dir = self.output_path.get().strip()

        # Output folder -> output file path (.xlsx)
        if not os.path.isdir(out_dir):
            messagebox.showerror("Error", "Output folder does not exist. Please select the folder again.")
            return

        base = os.path.splitext(os.path.basename(dst))[0] or "output"
        out_file = os.path.join(out_dir, f"{base}_result.xlsx")

        # If the output file already exists, ask before overwriting
        if os.path.exists(out_file):
            if not messagebox.askyesno("Overwrite?", f"The file already exists:\n{out_file}\n\nOverwrite?"):
                return

        self.status.set("Executing…")
        self._set_busy(True)

        def worker():
            try:
                written, not_found = main.run_transfer(src, dst, out_file)
                self.after(0, lambda: self._on_success(written, not_found, out_file))
            except Exception as e:
                msg = str(e)
                self.after(0, lambda msg=msg: self._on_error(msg))
            finally:
                self.after(0, lambda: self._set_busy(False))

        threading.Thread(target=worker, daemon=True).start()
    def _on_success(self, written: int, not_found: int, out: str):
        self.status.set(f"Ready. Written: {written}, not found: {not_found}\nSave to: {out}")
        messagebox.showinfo("Done", f"Ready.\n\nWritten: {written}\nNot found: {not_found}\n\nFile:\n{out}")

    def _on_error(self, msg: str):
        self.status.set(f"Error: {msg}")
        messagebox.showerror("Error", msg)

def main_gui():
    app = App()
    app.mainloop()


if __name__ == "__main__":
    main_gui()
