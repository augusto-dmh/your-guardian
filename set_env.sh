#!/bin/bash

prompt_environment() {
  echo "Please choose the environment:"
  echo "1) production"
  echo "2) testing"
  echo "3) development"
  read -p "Enter the number corresponding to your choice: " env_choice

  case $env_choice in
    1) ENV="production" ;;
    2) ENV="testing" ;;
    3) ENV="development" ;;
    *) echo -e "\nInvalid choice. Please run the script again and choose a valid option.\n"
       prompt_environment
       ;;
  esac
}

confirm_choice() {
  read -p $'\nYou have chosen '\''$ENV'\''. Are you sure? (y/n): ' confirm
  if [[ $confirm != [yY] ]]; then
    echo -e "\nOk, choose a different environment then.\n"
    prompt_environment
    confirm_choice
  fi
}

set_environment() {
    cp ".env.$ENV" .env
    echo -e "\n.env file for $ENV environment is set."
}

run() {
    prompt_environment
    confirm_choice
    set_environment
}

run

sleep 3
