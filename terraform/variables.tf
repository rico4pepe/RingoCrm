variable "mysql_image" {
  type    = string
  default = "mysql:8.0"
}

variable "mysql_root_password" {
  type    = string
  default = "root"
}

variable "mysql_database" {
  type    = string
  default = "ringocrm"
}

variable "mysql_user" {
  type    = string
  default = "rico"
}

variable "mysql_password" {
  type    = string
  default = "secret"
}

variable "mysql_external_port" {
  type    = number
  default = 3307
}

variable "redis_image" {
  type    = string
  default = "redis:7-alpine"
}

variable "redis_external_port" {
  type    = number
  default = 6379
}
