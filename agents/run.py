#!/usr/bin/env python3
"""
Launcher script for the AI Trading Platform agents.

Handles the module import path so you can run:
    python run.py          (from agents/ directory)
    python agents/run.py   (from project root)
"""

import sys
import os

# Ensure the parent directory is in sys.path so relative imports work
agents_dir = os.path.dirname(os.path.abspath(__file__))
project_root = os.path.dirname(agents_dir)

if project_root not in sys.path:
    sys.path.insert(0, project_root)

from agents.main import main

if __name__ == "__main__":
    main()
