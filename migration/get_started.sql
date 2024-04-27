/* COULD DELETE THIS FILE ONCE YOU HAVE FINISHED WORKING WITH DEMO */

INSERT INTO `history` (`id`, `id_movie`, `id_user`, `date`) VALUES
(1, 3, 1, '2024-04-26 13:44:07'),
(2, 2, 1, '2024-04-26 13:51:09'),
(3, 2, 1, '2024-04-26 13:51:13'),
(4, 6, 1, '2024-04-26 14:15:46'),
(5, 11, 1, '2024-04-26 16:18:35');

INSERT INTO `movie` (`id`, `title`, `id_genre`, `director`, `release_date`) VALUES
(1, 'The Matrix', 1, 'Lana Wachowski', '1999-03-31 00:00:00'),
(2, 'Inception', 1, 'Christopher Nolan', '2010-07-16 00:00:00'),
(3, 'The Shawshank Redemption', 2, 'Frank Darabont', '1994-09-23 00:00:00'),
(4, 'Forrest Gump', 2, 'Robert Zemeckis', '1994-07-06 00:00:00'),
(5, 'The Godfather', 3, 'Francis Ford Coppola', '1972-03-14 00:00:00'),
(6, 'Pulp Fiction', 3, 'Quentin Tarantino', '1994-10-14 00:00:00'),
(7, 'The Dark Knight', 1, 'Christopher Nolan', '2008-07-18 00:00:00'),
(8, 'Fight Club', 3, 'David Fincher', '1999-10-15 00:00:00'),
(9, 'Goodfellas', 3, 'Martin Scorsese', '1990-09-19 00:00:00'),
(10, 'The Lord of the Rings: The Return of the King', 4, 'Peter Jackson', '2003-12-17 00:00:00'),
(11, 'The Lord of the Rings: The Fellowship of the Ring', 4, 'Peter Jackson', '2001-12-19 00:00:00'),
(12, 'The Lord of the Rings: The Two Towers', 4, 'Peter Jackson', '2002-12-18 00:00:00'),
(13, 'Inglourious Basterds', 3, 'Quentin Tarantino', '2009-08-21 00:00:00'),
(14, 'The Silence of the Lambs', 5, 'Jonathan Demme', '1991-02-14 00:00:00'),
(15, 'The Green Mile', 2, 'Frank Darabont', '1999-12-10 00:00:00'),
(16, 'The Departed', 3, 'Martin Scorsese', '2006-10-06 00:00:00'),
(17, "Schindler's List", 6, 'Steven Spielberg', '1993-12-15 00:00:00'),
(18, 'The Prestige', 1, 'Christopher Nolan', '2006-10-20 00:00:00'),
(19, 'The Usual Suspects', 3, 'Bryan Singer', '1995-08-16 00:00:00'),
(20, 'Se7en', 3, 'David Fincher', '1995-09-22 00:00:00');

INSERT INTO `movie_genre` (`id`, `name`) VALUES
(1, 'Action'),
(2, 'Drama'),
(3, 'Crime'),
(4, 'Fantasy'),
(5, 'Thriller'),
(6, 'Biography');