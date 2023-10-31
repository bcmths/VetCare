-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 31/10/2023 às 18:31
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_vet`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_paciente`
--

CREATE TABLE `tb_paciente` (
  `id` int(11) NOT NULL,
  `tx_nome` varchar(255) NOT NULL,
  `tx_animal` varchar(50) DEFAULT NULL,
  `tx_raca` varchar(50) DEFAULT NULL,
  `tutor_id` int(11) NOT NULL,
  `vet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_paciente`
--

INSERT INTO `tb_paciente` (`id`, `tx_nome`, `tx_animal`, `tx_raca`, `tutor_id`, `vet_id`) VALUES
(6, 'Ralf', 'Cachorro', 'Beagle', 5, 8),
(7, 'Sasha', 'Gato', 'Siamesa', 5, 4),
(8, 'Márcio', 'Gato', 'Persa', 5, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_prontuario`
--

CREATE TABLE `tb_prontuario` (
  `id` int(11) NOT NULL,
  `tx_obs` text DEFAULT NULL,
  `paciente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_prontuario`
--

INSERT INTO `tb_prontuario` (`id`, `tx_obs`, `paciente_id`) VALUES
(11, 'Observação do paciente 1', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_sinaisclinicos`
--

CREATE TABLE `tb_sinaisclinicos` (
  `id` int(11) NOT NULL,
  `tx_descricao` text DEFAULT NULL,
  `paciente_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_sinaisclinicos`
--

INSERT INTO `tb_sinaisclinicos` (`id`, `tx_descricao`, `paciente_id`) VALUES
(1, 'Sinal Clínico 1 do Paciente 6', 6),
(2, 'Sinal Clínico 2 do Paciente 6', 6),
(3, 'Sinal Clínico 1 do Paciente 7', 7),
(4, 'Sinal Clínico 2 do Paciente 7', 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_tutor`
--

CREATE TABLE `tb_tutor` (
  `id` int(11) NOT NULL,
  `tx_nome` varchar(255) NOT NULL,
  `tx_email` varchar(255) DEFAULT NULL,
  `nb_telefone` varchar(20) DEFAULT NULL,
  `tx_endereco` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_tutor`
--

INSERT INTO `tb_tutor` (`id`, `tx_nome`, `tx_email`, `nb_telefone`, `tx_endereco`) VALUES
(5, 'Caio', 'caio@email.com', '987-654-3210', 'Endereço 2'),
(6, 'Matheus Barcelos de Carvalho', 'barcelosmatheusc@gmail.com', '61996647754', 'Condomínio Morada dos Nobres, S/N'),
(7, 'Rafaela Vilhena', 'rafaelalfvilhena@gmail.com', '61998496649', 'SHCES QUADRA 805 BLOCO A APTO 101');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `id` int(11) NOT NULL,
  `tx_usuario` varchar(50) NOT NULL,
  `tx_senha` varchar(255) NOT NULL,
  `vet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`id`, `tx_usuario`, `tx_senha`, `vet_id`) VALUES
(4, 'rafa', '123', 4),
(5, 'mat', '123', 5),
(10, 'marcio', '321', 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_vet`
--

CREATE TABLE `tb_vet` (
  `id` int(11) NOT NULL,
  `tx_nome` varchar(255) NOT NULL,
  `tx_genero` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_vet`
--

INSERT INTO `tb_vet` (`id`, `tx_nome`, `tx_genero`) VALUES
(4, 'Rafaela Vilhena', 'Feminino'),
(5, 'Matheus Barcelos', 'Masculino'),
(8, 'Dudu', 'Masculino'),
(10, 'Márcio', 'Masculino');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_paciente`
--
ALTER TABLE `tb_paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tutor_id` (`tutor_id`),
  ADD KEY `vet_id` (`vet_id`);

--
-- Índices de tabela `tb_prontuario`
--
ALTER TABLE `tb_prontuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_prontuario_ibfk_1` (`paciente_id`);

--
-- Índices de tabela `tb_sinaisclinicos`
--
ALTER TABLE `tb_sinaisclinicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`);

--
-- Índices de tabela `tb_tutor`
--
ALTER TABLE `tb_tutor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vet_id` (`vet_id`);

--
-- Índices de tabela `tb_vet`
--
ALTER TABLE `tb_vet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_paciente`
--
ALTER TABLE `tb_paciente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `tb_prontuario`
--
ALTER TABLE `tb_prontuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tb_sinaisclinicos`
--
ALTER TABLE `tb_sinaisclinicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tb_tutor`
--
ALTER TABLE `tb_tutor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_vet`
--
ALTER TABLE `tb_vet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_paciente`
--
ALTER TABLE `tb_paciente`
  ADD CONSTRAINT `tb_paciente_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tb_tutor` (`id`),
  ADD CONSTRAINT `tb_paciente_ibfk_2` FOREIGN KEY (`vet_id`) REFERENCES `tb_vet` (`id`);

--
-- Restrições para tabelas `tb_prontuario`
--
ALTER TABLE `tb_prontuario`
  ADD CONSTRAINT `tb_prontuario_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `tb_paciente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_sinaisclinicos`
--
ALTER TABLE `tb_sinaisclinicos`
  ADD CONSTRAINT `tb_sinaisclinicos_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `tb_paciente` (`id`);

--
-- Restrições para tabelas `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD CONSTRAINT `tb_usuario_ibfk_1` FOREIGN KEY (`vet_id`) REFERENCES `tb_vet` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
