#!/bin/bash

# 스크립트 설정
SCRIPT_NAME="kkoSendPy.py"
SCRIPT_PATH="/home/asssahcom9/webhook/$SCRIPT_NAME"
LOG_FILE="/home/asssahcom9/webhook/output.log"
PID_FILE="/home/asssahcom9/webhook/kkoSendPy.pid"

start() {
    if [ -f "$PID_FILE" ] && kill -0 $(cat "$PID_FILE"); then
        echo "$SCRIPT_NAME is already running."
        exit 1
    fi

    echo "Starting $SCRIPT_NAME..."
    nohup python3 "$SCRIPT_PATH" > "$LOG_FILE" 2>&1 &
    echo $! > "$PID_FILE"
    echo "$SCRIPT_NAME started with PID $(cat $PID_FILE)."
}

stop() {
    if [ ! -f "$PID_FILE" ] || ! kill -0 $(cat "$PID_FILE"); then
        echo "$SCRIPT_NAME is not running."
        exit 1
    fi

    echo "Stopping $SCRIPT_NAME..."
    kill $(cat "$PID_FILE") && rm -f "$PID_FILE"
    echo "$SCRIPT_NAME stopped."
}

status() {
    if [ -f "$PID_FILE" ] && kill -0 $(cat "$PID_FILE"); then
        echo "$SCRIPT_NAME is running with PID $(cat $PID_FILE)."
    else
        echo "$SCRIPT_NAME is not running."
    fi
}

case "$1" in
    start)
        start
        ;;
    stop)
        stop
        ;;
    status)
        status
        ;;
    restart)
        stop
        start
        ;;
    *)
        echo "Usage: $0 {start|stop|status|restart}"
        exit 1
        ;;
esac
