FROM elixir:1.15

# Install debian packages
RUN apt-get update && \
    apt-get install -y postgresql-client inotify-tools && \
    rm -rf /var/lib/apt/lists/*

# Install Phoenix packages
RUN mix local.hex --force && \
    mix local.rebar --force

WORKDIR /app

# Install mix dependencies
COPY mix.exs mix.lock ./
RUN mix deps.get

COPY . .
RUN mix deps.get


EXPOSE 4000

CMD ["mix", "phx.server"]
