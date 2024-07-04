#!/bin/sh

ssh-keyscan $REMOTE_HOST >> ~/.ssh/known_hosts

ssh -4 -i $SSH_ID_FILE -N $SSH_USER@$REMOTE_HOST -L *:$LOCAL_PORT:127.0.0.1:$REMOTE_PORT
