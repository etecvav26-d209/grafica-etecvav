-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20/11/2026 às 03:20
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `grafica`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `armario`
--

CREATE TABLE `armario` (
  `id` int(11) UNSIGNED NOT NULL,
  `numero` varchar(10) NOT NULL,
  `tamanho` enum('grande','pequeno') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `armario`
--

INSERT INTO `armario` (`id`, `numero`, `tamanho`) VALUES
(1, 'G001', 'grande'),
(2, 'G002', 'grande'),
(3, 'G003', 'grande'),
(4, 'G004', 'grande'),
(5, 'G005', 'grande'),
(6, 'G006', 'grande'),
(7, 'G007', 'grande'),
(8, 'G008', 'grande'),
(9, 'G009', 'grande'),
(10, 'G010', 'grande'),
(11, 'G011', 'grande'),
(12, 'G012', 'grande'),
(13, 'G013', 'grande'),
(14, 'G014', 'grande'),
(15, 'G015', 'grande'),
(16, 'G016', 'grande'),
(17, 'G017', 'grande'),
(18, 'G018', 'grande'),
(19, 'G019', 'grande'),
(20, 'G020', 'grande'),
(21, 'G021', 'grande'),
(22, 'G022', 'grande'),
(23, 'G023', 'grande'),
(24, 'G024', 'grande'),
(25, 'G025', 'grande'),
(26, 'G026', 'grande'),
(27, 'G027', 'grande'),
(28, 'G028', 'grande'),
(29, 'G029', 'grande'),
(30, 'G030', 'grande'),
(31, 'G031', 'grande'),
(32, 'G032', 'grande'),
(33, 'G033', 'grande'),
(34, 'G034', 'grande'),
(35, 'G035', 'grande'),
(36, 'G036', 'grande'),
(37, 'G037', 'grande'),
(38, 'G038', 'grande'),
(39, 'G039', 'grande'),
(40, 'G040', 'grande'),
(41, 'G041', 'grande'),
(42, 'G042', 'grande'),
(43, 'G043', 'grande'),
(44, 'G044', 'grande'),
(45, 'G045', 'grande'),
(46, 'G046', 'grande'),
(47, 'G047', 'grande'),
(48, 'G048', 'grande'),
(49, 'G049', 'grande'),
(50, 'G050', 'grande'),
(51, 'G051', 'grande'),
(52, 'G052', 'grande'),
(53, 'G053', 'grande'),
(54, 'G054', 'grande'),
(55, 'G055', 'grande'),
(56, 'G056', 'grande'),
(57, 'G057', 'grande'),
(58, 'G058', 'grande'),
(59, 'G059', 'grande'),
(60, 'G060', 'grande'),
(61, 'G061', 'grande'),
(62, 'G062', 'grande'),
(63, 'G063', 'grande'),
(64, 'G064', 'grande'),
(65, 'G065', 'grande'),
(66, 'G066', 'grande'),
(67, 'G067', 'grande'),
(68, 'G068', 'grande'),
(69, 'G069', 'grande'),
(70, 'G070', 'grande'),
(71, 'G071', 'grande'),
(72, 'G072', 'grande'),
(73, 'G073', 'grande'),
(74, 'G074', 'grande'),
(75, 'G075', 'grande'),
(76, 'G076', 'grande'),
(77, 'G077', 'grande'),
(78, 'G078', 'grande'),
(79, 'G079', 'grande'),
(80, 'G080', 'grande'),
(81, 'G081', 'grande'),
(82, 'G082', 'grande'),
(83, 'G083', 'grande'),
(84, 'G084', 'grande'),
(85, 'G085', 'grande'),
(86, 'G086', 'grande'),
(87, 'G087', 'grande'),
(88, 'G088', 'grande'),
(89, 'G089', 'grande'),
(90, 'G090', 'grande'),
(91, 'G091', 'grande'),
(92, 'G092', 'grande'),
(93, 'G093', 'grande'),
(94, 'G094', 'grande'),
(95, 'G095', 'grande'),
(96, 'G096', 'grande'),
(97, 'G097', 'grande'),
(98, 'G098', 'grande'),
(99, 'G099', 'grande'),
(100, 'G100', 'grande'),
(101, 'G101', 'grande'),
(102, 'G102', 'grande'),
(103, 'G103', 'grande'),
(104, 'G104', 'grande'),
(105, 'G105', 'grande'),
(106, 'G106', 'grande'),
(107, 'G107', 'grande'),
(108, 'G108', 'grande'),
(109, 'G109', 'grande'),
(110, 'G110', 'grande'),
(111, 'G111', 'grande'),
(112, 'G112', 'grande'),
(113, 'G113', 'grande'),
(114, 'G114', 'grande'),
(115, 'G115', 'grande'),
(116, 'G116', 'grande'),
(117, 'G117', 'grande'),
(118, 'G118', 'grande'),
(119, 'G119', 'grande'),
(120, 'G120', 'grande'),
(121, 'G121', 'grande'),
(122, 'G122', 'grande'),
(123, 'G123', 'grande'),
(124, 'G124', 'grande'),
(125, 'G125', 'grande'),
(126, 'G126', 'grande'),
(127, 'G127', 'grande'),
(128, 'G128', 'grande'),
(129, 'G129', 'grande'),
(130, 'G130', 'grande'),
(131, 'G131', 'grande'),
(132, 'G132', 'grande'),
(133, 'G133', 'grande'),
(134, 'G134', 'grande'),
(135, 'G135', 'grande'),
(136, 'G136', 'grande'),
(137, 'G137', 'grande'),
(138, 'G138', 'grande'),
(139, 'G139', 'grande'),
(140, 'G140', 'grande'),
(141, 'G141', 'grande'),
(142, 'G142', 'grande'),
(143, 'G143', 'grande'),
(144, 'G144', 'grande'),
(145, 'G145', 'grande'),
(146, 'G146', 'grande'),
(147, 'G147', 'grande'),
(148, 'G148', 'grande'),
(149, 'G149', 'grande'),
(150, 'G150', 'grande'),
(151, 'G151', 'grande'),
(152, 'G152', 'grande'),
(153, 'G153', 'grande'),
(154, 'G154', 'grande'),
(155, 'G155', 'grande'),
(156, 'G156', 'grande'),
(157, 'G157', 'grande'),
(158, 'G158', 'grande'),
(159, 'G159', 'grande'),
(160, 'G160', 'grande'),
(161, 'G161', 'grande'),
(162, 'G162', 'grande'),
(163, 'G163', 'grande'),
(164, 'G164', 'grande'),
(165, 'G165', 'grande'),
(166, 'G166', 'grande'),
(167, 'G167', 'grande'),
(168, 'G168', 'grande'),
(169, 'G169', 'grande'),
(170, 'G170', 'grande'),
(171, 'G171', 'grande'),
(172, 'G172', 'grande'),
(173, 'G173', 'grande'),
(174, 'G174', 'grande'),
(175, 'G175', 'grande'),
(176, 'G176', 'grande'),
(177, 'G177', 'grande'),
(178, 'G178', 'grande'),
(179, 'G179', 'grande'),
(180, 'G180', 'grande'),
(181, 'G181', 'grande'),
(182, 'G182', 'grande'),
(183, 'G183', 'grande'),
(184, 'G184', 'grande'),
(185, 'G185', 'grande'),
(186, 'G186', 'grande'),
(187, 'G187', 'grande'),
(188, 'G188', 'grande'),
(189, 'G189', 'grande'),
(190, 'G190', 'grande'),
(191, 'G191', 'grande'),
(192, 'G192', 'grande'),
(193, 'G193', 'grande'),
(194, 'G194', 'grande'),
(195, 'G195', 'grande'),
(196, 'G196', 'grande'),
(197, 'G197', 'grande'),
(198, 'G198', 'grande'),
(199, 'G199', 'grande'),
(200, 'G200', 'grande'),
(201, 'P001', 'pequeno'),
(202, 'P002', 'pequeno'),
(203, 'P003', 'pequeno'),
(204, 'P004', 'pequeno'),
(205, 'P005', 'pequeno'),
(206, 'P006', 'pequeno'),
(207, 'P007', 'pequeno'),
(208, 'P008', 'pequeno'),
(209, 'P009', 'pequeno'),
(210, 'P010', 'pequeno'),
(211, 'P011', 'pequeno'),
(212, 'P012', 'pequeno'),
(213, 'P013', 'pequeno'),
(214, 'P014', 'pequeno'),
(215, 'P015', 'pequeno'),
(216, 'P016', 'pequeno');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `perfil` enum('administrador','gerente','atendente','operador') NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT current_timestamp(),
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `usuario`, `senha_hash`, `perfil`, `ativo`, `data_cadastro`) VALUES
(1, 'Administrador de teste', 'admin', '$2y$10$pE6a6kNaegCE4trw/uXdM.FfZiSwcWhvlxlWwPxAeJqofws6IfwcK', 'administrador', 1, '2026-11-20 02:19:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_venda`
--

CREATE TABLE `itens_venda` (
  `id` int(11) UNSIGNED NOT NULL,
  `venda_id` int(11) UNSIGNED NOT NULL,
  `produto_id` int(11) UNSIGNED NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `manutencoes`
--

CREATE TABLE `manutencoes` (
  `id` int(11) UNSIGNED NOT NULL,
  `equipamento` varchar(100) NOT NULL,
  `descricao_problema` text NOT NULL,
  `data_registro` date NOT NULL,
  `funcionario_id` int(11) UNSIGNED NOT NULL,
  `status` enum('aberta','em andamento','concluída') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes_estoque`
--

CREATE TABLE `movimentacoes_estoque` (
  `id` int(11) UNSIGNED NOT NULL,
  `produto_id` int(11) UNSIGNED NOT NULL,
  `funcionario_id` int(11) UNSIGNED NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `quantidade` int(11) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `data_movimentacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome_solicitante` varchar(100) NOT NULL,
  `sala` varchar(30) DEFAULT NULL,
  `curso_cargo` varchar(100) DEFAULT NULL,
  `servico_id` int(11) UNSIGNED NOT NULL,
  `quantidade` int(11) NOT NULL,
  `formato` varchar(50) DEFAULT NULL,
  `prazo` date DEFAULT NULL,
  `valor_cobrado` decimal(10,2) NOT NULL,
  `status` enum('recebido','em produção','pronto','entregue') NOT NULL,
  `funcionario_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo` enum('revenda','insumo') NOT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `estoque_atual` int(11) NOT NULL DEFAULT 0,
  `estoque_minimo` int(11) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `tipo`, `preco`, `estoque_atual`, `estoque_minimo`, `ativo`, `data_cadastro`) VALUES
(1, 'Lápis', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(2, 'Caneta', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(3, 'Borracha', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(4, 'Apontador', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(5, 'Régua de 30 cm', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(6, 'Marca-texto', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(7, 'Folha A4', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(8, 'Folha A3', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(9, 'Folha A2', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(10, 'Camiseta', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43'),
(11, 'Moletom', 'revenda', NULL, 0, 0, 1, '2026-11-20 00:23:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios`
--

CREATE TABLE `relatorios` (
  `id` int(11) UNSIGNED NOT NULL,
  `tipo` enum('pedidos','vendas') NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `data_geracao` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_registros` int(11) NOT NULL DEFAULT 0,
  `total_valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `funcionario_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas_armario`
--

CREATE TABLE `reservas_armario` (
  `id` int(11) UNSIGNED NOT NULL,
  `estudante` varchar(100) NOT NULL,
  `sala` varchar(30) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `armario_id` int(11) UNSIGNED NOT NULL,
  `ano_letivo` year(4) NOT NULL,
  `data_reserva` date NOT NULL,
  `situacao_pgmento` enum('pendente','pago') NOT NULL,
  `funcionario_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos_impressao`
--

CREATE TABLE `servicos_impressao` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) UNSIGNED NOT NULL,
  `funcionario_id` int(11) UNSIGNED NOT NULL,
  `data_venda` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `armario`
--
ALTER TABLE `armario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices de tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venda_id` (`venda_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `manutencoes`
--
ALTER TABLE `manutencoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- Índices de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `servico_id` (`servico_id`),
  ADD KEY `funcionario_id` (`funcionario_id`),
  ADD KEY `servico_id_2` (`servico_id`,`funcionario_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `relatorios`
--
ALTER TABLE `relatorios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- Índices de tabela `reservas_armario`
--
ALTER TABLE `reservas_armario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `armario_id` (`armario_id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- Índices de tabela `servicos_impressao`
--
ALTER TABLE `servicos_impressao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `armario`
--
ALTER TABLE `armario`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `itens_venda`
--
ALTER TABLE `itens_venda`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `manutencoes`
--
ALTER TABLE `manutencoes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `relatorios`
--
ALTER TABLE `relatorios`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reservas_armario`
--
ALTER TABLE `reservas_armario`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos_impressao`
--
ALTER TABLE `servicos_impressao`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_venda`
--
ALTER TABLE `itens_venda`
  ADD CONSTRAINT `itens_venda_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`),
  ADD CONSTRAINT `itens_venda_ibfk_2` FOREIGN KEY (`venda_id`) REFERENCES `vendas` (`id`);

--
-- Restrições para tabelas `manutencoes`
--
ALTER TABLE `manutencoes`
  ADD CONSTRAINT `manutencoes_ibfk_1` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`);

--
-- Restrições para tabelas `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  ADD CONSTRAINT `movimentacoes_estoque_ibfk_1` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`),
  ADD CONSTRAINT `movimentacoes_estoque_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos_impressao` (`id`);

--
-- Restrições para tabelas `relatorios`
--
ALTER TABLE `relatorios`
  ADD CONSTRAINT `relatorios_ibfk_1` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`);

--
-- Restrições para tabelas `reservas_armario`
--
ALTER TABLE `reservas_armario`
  ADD CONSTRAINT `reservas_armario_ibfk_1` FOREIGN KEY (`armario_id`) REFERENCES `armario` (`id`),
  ADD CONSTRAINT `reservas_armario_ibfk_2` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`);

--
-- Restrições para tabelas `vendas`
--
ALTER TABLE `vendas`
  ADD CONSTRAINT `vendas_ibfk_1` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
