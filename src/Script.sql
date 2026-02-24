CREATE DATABASE IF NOT EXISTS project_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE project_php;

CREATE TABLE IF NOT EXISTS posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content LONGTEXT NOT NULL,
  category ENUM('cocina','viajes','gaming') NOT NULL,
  read_time INT,
  created_at DATE,
  tags VARCHAR(255),
  INDEX idx_category (category),
  INDEX idx_created_at (created_at),
  FULLTEXT INDEX ft_title_content (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO posts (title, content, category, read_time, created_at, tags)
VALUES
(
 '3 recetas rápidas para cuando no quieres complicarte',
 'Pasta al ajo, quesadillas con guacamole y avena con frutas...',
 'cocina',
 7,
 '2026-01-19',
 'pasta,recetas,rápidas'
);

-- Cocina posts
INSERT INTO posts (title, content, category, read_time, created_at, tags) VALUES
(
  'Desayunos rápidos para antes de clases',
  'Si no tienes mucho tiempo por la mañana, estos desayunos te salvan: licuado de plátano con avena, tostadas con huevo y fruta picada con yogurt.',
  'cocina',
  5,
  '2026-01-10',
  'desayuno,avena,rápido,estudiantes'
),
(
  'Comidas baratas para la semana',
  'Planear comidas económicas no significa comer mal. Arroz con verduras, lentejas y pollo al horno pueden rendir toda la semana.',
  'cocina',
  6,
  '2026-01-12',
  'comida,barato,planificación'
);

-- Viajes posts
INSERT INTO posts (title, content, category, read_time, created_at, tags) VALUES
(
  'Cómo viajar ligero sin olvidar lo importante',
  'Viajar ligero te ahorra estrés. Lleva ropa versátil, evita zapatos extra y usa bolsas pequeñas para organizar todo.',
  'viajes',
  4,
  '2026-01-08',
  'viajar,ligero,maleta,organización'
),
(
  'Lugares económicos para visitar un fin de semana',
  'No necesitas mucho dinero para salir. Pueblos mágicos, playas cercanas y zonas naturales son excelentes opciones.',
  'viajes',
  5,
  '2026-01-14',
  'fin de semana,económico,escapada'
);

-- Gaming post
INSERT INTO posts (title, content, category, read_time, created_at, tags) VALUES
(
  'Errores comunes al jugar muchas horas',
  'Jugar demasiado puede afectar tu rendimiento. Evita sesiones eternas, descansa la vista y mantén horarios claros.',
  'gaming',
  6,
  '2026-01-09',
  'gaming,salud,tiempo,descanso'
),
(
  'Cómo equilibrar videojuegos y estudios',
  'Estudiar y jugar sí es posible. Establece metas diarias, usa el gaming como recompensa y mantén una rutina.',
  'gaming',
  7,
  '2026-01-16',
  'rutina,estudio,gamer,organización'
);
INSERT INTO posts (title, content, category, read_time, created_at, tags) VALUES
(
  'Organiza tu tiempo libre en solo 30 minutos',
  'Dedicar 30 minutos al día a un hobby mejora tu ánimo. Puedes cocinar algo rápido, jugar una partida o planear un viaje.',
  'gaming',
  5,
  '2026-01-18',
  'tiempo,hobbies,organización,30min'
);

-- Add user admin
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario admin solo si no existe
INSERT IGNORE INTO users (username, password)
VALUES (
  'admin',
  '$2y$10$zaja0Wbaf13rG7qcLaT20.DLshp4MJexT2O.hcklVk8c5umGvw7pK'
);



