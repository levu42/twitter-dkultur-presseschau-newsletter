#!/bin/sh
w3m -dump -cols 2000 http://srv.deutschlandradio.de/presseschau.370.de.mobiletextonly 2>/dev/null | tail -n+8 | less | head -n -2
