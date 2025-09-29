output "containers" {
  value = {
    mysql = docker_container.mysql.name
    redis = docker_container.redis.name
  }
}

output "mysql_host" {
  value = "localhost"
}

output "mysql_port" {
  value = docker_container.mysql.ports[0].external
}
