-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30-Mar-2026 às 18:14
-- Versão do servidor: 10.4.25-MariaDB
-- versão do PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_estoqueflow`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nome_categoria` varchar(100) NOT NULL,
  `dataCaptura` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `id_usuario`, `nome_categoria`, `dataCaptura`) VALUES
(4, 6, 'Bermudas', '2018-05-17'),
(6, 6, 'Sapatos', '2018-05-17'),
(7, 6, 'Camisas', '2018-05-17'),
(15, 7, 'Barraca', '2026-02-12'),
(16, 7, 'Varas', '2026-03-17'),
(17, 7, 'calça', '2026-03-30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(100) NOT NULL,
  `cpf` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `id_usuario`, `nome`, `sobrenome`, `endereco`, `email`, `telefone`, `cpf`) VALUES
(4, 6, 'Paula F', 'Santos', 'Rua A', 'paula@hotmail.com', '555555', '33333'),
(5, 6, 'Hugo Vasconcelos', 'Freitas', 'Rua C', 'hugovasconcelosf', '988878', '8787878'),
(15, 7, 'Felipe', 'Paulo', 'rua3', 'felipe@gmail.com', '222222222', '211112212222'),
(16, 7, 'Izaquiel', 'Sena', 'QD 68 LT 28', 'izaquielpereiradesena2@gmail.com', '62981370543', '069.464.821-31');

-- --------------------------------------------------------

--
-- Estrutura da tabela `entradas_estoque`
--

CREATE TABLE `entradas_estoque` (
  `id_entrada` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `data_entrada` date NOT NULL,
  `dataCaptura` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `entradas_estoque`
--

INSERT INTO `entradas_estoque` (`id_entrada`, `id_produto`, `id_usuario`, `quantidade`, `preco`, `data_entrada`, `dataCaptura`) VALUES
(1, 35, 7, 100, '30.99', '2026-03-17', '2026-03-17'),
(2, 35, 7, -5, '30.99', '2026-03-17', '2026-03-17'),
(3, 36, 7, 50, '300.99', '2026-03-17', '2026-03-17'),
(4, 37, 7, 100, '100.66', '2026-03-17', '2026-03-17'),
(5, 37, 7, -10, '100.66', '2026-03-17', '2026-03-17');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id_fornecedor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `rasaosocial` varchar(100) NOT NULL,
  `nomefantasia` varchar(100) NOT NULL,
  `endereco` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(100) NOT NULL,
  `cnpj` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id_fornecedor`, `id_usuario`, `rasaosocial`, `nomefantasia`, `endereco`, `email`, `telefone`, `cnpj`) VALUES
(1, 6, 'Pedro Freitas', 'Vasconcelos', 'Rua 5', 'pedro@hotmail.com', '63333', '555555'),
(3, 6, 'Fábio Freitas', 'Freitas', 'Rua D', 'fabio@hotmail.com', '3333333', '555555'),
(6, 7, 'cscsc', 'xz', 'safas', 'fas', 'afAS', 'SAFASF'),
(10, 7, 'qwq', 'qwqw', 'qwqw', 'wqwq', '11651651', '55112'),
(11, 7, 'zxzxzx', 'zxzxzx', 'zxzxzx', 'zxzxzx', '11212122', '112121212');

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagens`
--

CREATE TABLE `imagens` (
  `id_imagem` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `dataUpload` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `imagens`
--

INSERT INTO `imagens` (`id_imagem`, `id_categoria`, `nome`, `url`, `dataUpload`) VALUES
(8, 5, 'tenis.jpg', '../../arquivos/tenis.jpg', '2018-05-22'),
(19, 15, 'Captura de tela 2025-11-19 100813.png', '../../arquivos/Captura de tela 2025-11-19 100813.png', '2026-02-23'),
(35, 16, 'anzol.png', '../../arquivos/anzol.png', '2026-03-17'),
(36, 15, 'barrraca.webp', '../../arquivos/barrraca.webp', '2026-03-17'),
(37, 15, 'barrraca.webp', '../../arquivos/barrraca.webp', '2026-03-17'),
(39, 6, 'anzol.png', '../../arquivos/anzol.png', '2026-03-30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_imagem` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `dataCaptura` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `id_categoria`, `id_imagem`, `id_usuario`, `nome`, `descricao`, `dataCaptura`) VALUES
(35, 16, 35, 7, 'Vara azul', '37 cm', '2026-03-17'),
(36, 15, 36, 7, 'Amarela', '5 Pessoas', '2026-03-17'),
(37, 15, 37, 7, 'Barraca de acampamento', 'vermelha', '2026-03-17'),
(39, 6, 39, 7, 'rre', 'ergr', '2026-03-30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `user` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `dataCaptura` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `user`, `email`, `senha`, `dataCaptura`) VALUES
(6, 'Hugo Freitas', 'hugofreitas', 'hugovasconcelosf@hotmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2018-05-17'),
(7, 'admin', 'admin', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '2018-05-17'),
(9, 'izaquiel', 'izaquiel.sena', 'izaquiel@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2025-11-07'),
(10, 'Pedro', 'pedro.souza', 'pedro@gmail.com', '4c9c79eaad0ffc714f21ea12b075a60d1cbc7d9e', '2025-12-08');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

CREATE TABLE `vendas` (
  `id_venda` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `total_venda` decimal(10,2) DEFAULT NULL,
  `dataCompra` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `vendas`
--

INSERT INTO `vendas` (`id_venda`, `id_cliente`, `id_produto`, `id_usuario`, `preco`, `quantidade`, `total_venda`, `dataCompra`) VALUES
(1, 4, 30, 7, '20.60', 2, '41.20', '2026-02-25'),
(11, 0, 35, 7, '30.99', 5, '154.95', '2026-03-17'),
(12, 15, 37, 7, '100.66', 10, '1006.60', '2026-03-17'),
(13, 4, 38, 7, '101.30', 15, '1519.50', '2026-03-30');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices para tabela `entradas_estoque`
--
ALTER TABLE `entradas_estoque`
  ADD PRIMARY KEY (`id_entrada`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices para tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id_fornecedor`);

--
-- Índices para tabela `imagens`
--
ALTER TABLE `imagens`
  ADD PRIMARY KEY (`id_imagem`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id_venda`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `entradas_estoque`
--
ALTER TABLE `entradas_estoque`
  MODIFY `id_entrada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id_fornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id_imagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id_venda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `entradas_estoque`
--
ALTER TABLE `entradas_estoque`
  ADD CONSTRAINT `entradas_estoque_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`) ON DELETE CASCADE,
  ADD CONSTRAINT `entradas_estoque_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
