-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/11/2023 às 21:49
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
(1, 'Bella', 'Cachorro', 'Labrador Retriever', 2, 45),
(2, 'Thor', 'Gato', 'Siamês', 2, 45),
(3, 'Luna', 'Cachorro', 'Bulldog Francês', 3, 47),
(4, 'Milo', 'Gato', 'Persa', 4, 44),
(5, 'Daisy', 'Cachorro', 'Golden Retriever', 5, 48),
(6, 'Max', 'Cachorro', 'Poodle', 1, 44),
(7, 'Nina', 'Gato', 'Maine Coon', 2, 47),
(8, 'Rocky', 'Cachorro', 'Bulldog Inglês', 3, 47),
(9, 'Cleo', 'Gato', 'Bengal', 4, 47),
(10, 'Charlie', 'Cachorro', 'Dachshund', 5, 48);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_prontuario`
--

CREATE TABLE `tb_prontuario` (
  `id` int(11) NOT NULL,
  `tx_obs` text DEFAULT NULL,
  `paciente_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_prontuario`
--

INSERT INTO `tb_prontuario` (`id`, `tx_obs`, `paciente_id`) VALUES
(1, 'Administrar medicamento contra pulgas e carrapatos', 1),
(2, 'Recomendar exames pulmonares para avaliar a tosse', 7),
(3, 'Prescrever tratamento dermatológico para alergia', 3),
(4, 'Realizar exames gastrointestinais para investigar vômitos', 4),
(5, 'Avaliar exames metabólicos para diagnosticar letargia', 5),
(6, 'Iniciar tratamento para infecção respiratória', 1),
(7, 'Sessões de treinamento comportamental recomendadas', 2),
(8, 'Prescrever colírio para tratar infecção ocular', 3),
(9, 'Consultar urologista para avaliação de dificuldade urinária', 10),
(10, 'Tratamento dermatológico para aliviar coceira nas orelhas', 10);

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
(1, 'Coceira persistente e perda de pelos', 8),
(2, 'Dificuldade para respirar e tosse seca', 10),
(3, 'Lambedura excessiva das patas traseiras', 3),
(4, 'Vômitos frequentes após as refeições', 4),
(5, 'Letargia e falta de apetite', 5),
(6, 'Tosse seca e olhos lacrimejantes', 1),
(7, 'Comportamento agressivo e rosnados', 2),
(8, 'Secreção ocular e espirros frequentes', 3),
(9, 'Dificuldade para urinar e miau frequente', 4),
(10, 'Coceira intensa nas orelhas e sacudidas frequentes da cabeça', 5),
(11, 'Vômitos com sangue e apatia', 1),
(12, 'Dificuldade para se movimentar e dor nas articulações', 2),
(13, 'Aumento da sede e micção frequente', 3),
(14, 'Pelagem opaca e perda de peso', 4),
(15, 'Dificuldade para defecar e letargia', 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_tutor`
--

CREATE TABLE `tb_tutor` (
  `id` int(11) NOT NULL,
  `tx_nome` varchar(255) NOT NULL,
  `tx_email` varchar(255) DEFAULT NULL,
  `nb_telefone` varchar(20) DEFAULT NULL,
  `tx_endereco` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_tutor`
--

INSERT INTO `tb_tutor` (`id`, `tx_nome`, `tx_email`, `nb_telefone`, `tx_endereco`) VALUES
(1, 'Ana Silva', 'ana@email.com', '123456789', 'Rua A, 123'),
(2, 'Carlos Oliveira', 'carlos@email.com', '987654321', 'Av. B, 456'),
(3, 'Fernanda Santos', 'fernanda@email.com', '123987456', 'Rua C, 789'),
(4, 'Diego Pereira', 'diego@email.com', '456789123', 'Av. D, 101'),
(5, 'Julia Souza', 'julia@email.com', '789123456', 'Rua E, 202'),
(8, 'Matheus Barcelos de Carvalho', 'barcelosmatheusc@gmail.com', '61996647754', 'Condomínio Morada dos Nobres, S/N');

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
(22, 'tiagolima', 'lima2022!', 44),
(23, 'isabelamartins', 'senha1234', 45),
(25, 'amandasilva', 'senhaAmanda', 47),
(26, 'mat', '123', 48);

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
(44, 'Tiago Lima', 'Masculino'),
(45, 'Isabela Martins', 'Feminino'),
(47, 'Amanda Silva', 'Feminino'),
(48, 'Matheus Barcelos de Carvalho', 'Masculino');

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
  ADD KEY `paciente_id` (`paciente_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_prontuario`
--
ALTER TABLE `tb_prontuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_sinaisclinicos`
--
ALTER TABLE `tb_sinaisclinicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `tb_tutor`
--
ALTER TABLE `tb_tutor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `tb_vet`
--
ALTER TABLE `tb_vet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

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
  ADD CONSTRAINT `tb_prontuario_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `tb_paciente` (`id`);

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
