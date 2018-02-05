FROM ety001/steem-mention:base
COPY watcher/bots.py /app/bots.py
CMD ["python3 /app/bots.py"]
