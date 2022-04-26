#ENV
if [ ! -f ./.env ]; then \
    cp ./.env.dist ./.env
    echo "Create .env file";
fi

chmod -R a+w ./.docker/grafana