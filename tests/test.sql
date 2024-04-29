CREATE SCHEMA IF NOT EXISTS FrameworkComponentTest;

USE FrameworkComponentTest;

DROP TABLE IF EXISTS Article, User;

CREATE TABLE User (
    intUserId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    strUsername VARCHAR(60) NOT NULL UNIQUE,
    strEmailAddress VARCHAR(255) NOT NULL UNIQUE,
    strPassword VARCHAR(255) NOT NULL
);

CREATE TABLE Article (
    intArticleId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    strTitle VARCHAR(255) NOT NULL,
    intUserId INT(11) UNSIGNED NOT NULL,
    dtmCreated DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (intUserId) REFERENCES User(intUserId)
);

CREATE TABLE Permission (
    intPermissionId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    strName VARCHAR(255) NOT NULL,
    strHandle VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE Role (
    intRoleId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    strName VARCHAR(255) NOT NULL,
    strHandle VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE RoleUser (
    intRoleId INT(11) UNSIGNED NOT NULL,
    intUserId INT(11) UNSIGNED NOT NULL,
    FOREIGN KEY (intRoleId) REFERENCES Role(intRoleId),
    FOREIGN KEY (intUserId) REFERENCES User(intUserId),
    UNIQUE KEY (intRoleId, intUserId)
);

CREATE TABLE PermissionRole (
    intPermissionId INT(11) UNSIGNED NOT NULL,
    intRoleId INT(11) UNSIGNED NOT NULL,
    FOREIGN KEY (intPermissionId) REFERENCES Permission(intPermissionId),
    FOREIGN KEY (intRoleId) REFERENCES Role(intRoleId),
    UNIQUE KEY (intPermissionId, intRoleId)
);