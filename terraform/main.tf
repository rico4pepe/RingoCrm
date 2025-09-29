terraform {
  required_providers {
    docker = {
      source  = "kreuzwerker/docker"
      version = "~> 3.0"
    }
  }
  required_version = ">= 1.1.0"
}

provider "docker" {
  # using local Docker socket (Docker Desktop). No config needed for local usage.
}

resource "docker_network" "laravel_net" {
  name = "laravel_network"
}

resource "docker_volume" "mysql_data" {
  name = "mysql_data"
}

resource "docker_volume" "redis_data" {
  name = "redis_data"
}

resource "docker_container" "mysql" {
  name  = "laravel_db"
  image = var.mysql_image

  env = [
    "MYSQL_ROOT_PASSWORD=${var.mysql_root_password}",
    "MYSQL_DATABASE=${var.mysql_database}",
    "MYSQL_USER=${var.mysql_user}",
    "MYSQL_PASSWORD=${var.mysql_password}",
  ]

  ports {
    internal = 3306
    external = var.mysql_external_port
  }

  networks_advanced {
    name = docker_network.laravel_net.name
  }

  mounts {
    target = "/var/lib/mysql"
    source = docker_volume.mysql_data.name
    type   = "volume"
  }

  restart = "unless-stopped"
}

resource "docker_container" "redis" {
  name  = "redis"
  image = var.redis_image

  ports {
    internal = 6379
    external = var.redis_external_port
  }

  networks_advanced {
    name = docker_network.laravel_net.name
  }

  mounts {
    target = "/data"
    source = docker_volume.redis_data.name
    type   = "volume"
  }

  restart = "unless-stopped"
}
