<?php

namespace Mouf\Security\Password;

use Mouf\Html\Renderer\RendererUtils;
use Mouf\Installer\PackageInstallerInterface;
use Mouf\MoufManager;

class ForgotYourPasswordInstaller implements PackageInstallerInterface
{
    /**
     * (non-PHPdoc).
     *
     * @see \Mouf\Installer\PackageInstallerInterface::install()
     */
    public static function install(MoufManager $moufManager)
    {
        // Let's create the renderer
        RendererUtils::createPackageRenderer($moufManager, 'mouf/security.forgot-your-password');

        // These instances are expected to exist when the installer is run.
        $defaultTranslationService = $moufManager->getInstanceDescriptor('defaultTranslationService');
        if ($moufManager->has('Mouf\\Security\\DAO\\SecurityUserDao')) {
            $Mouf_Security_DAO_SecurityUserDao = $moufManager->getInstanceDescriptor('Mouf\\Security\\DAO\\SecurityUserDao');
        } else {
            $Mouf_Security_DAO_SecurityUserDao = null;
        }
        $swiftMailer = $moufManager->getInstanceDescriptor('swiftMailer');
        $forgotYourPasswordMailTemplate = $moufManager->getInstanceDescriptor('forgotYourPasswordMailTemplate');
        $userService = $moufManager->getInstanceDescriptor('userService');
        $psr_errorLogLogger = $moufManager->getInstanceDescriptor('psr.errorLogLogger');
        $bootstrapTemplate = $moufManager->getInstanceDescriptor('bootstrapTemplate');
        $block_content = $moufManager->getInstanceDescriptor('block.content');
        $twigEnvironment = $moufManager->getInstanceDescriptor('twigEnvironment');

        // Let's create the instances.
        $Mouf_Security_Password_PasswordStrengthCheck = InstallUtils::getOrCreateInstance('Mouf\\Security\\Password\\PasswordStrengthCheck', 'Mouf\\Security\\Password\\PasswordStrengthCheck', $moufManager);
        $Mouf_Security_Password_ForgotYourPasswordService = InstallUtils::getOrCreateInstance('Mouf\\Security\\Password\\ForgotYourPasswordService', 'Mouf\\Security\\Password\\ForgotYourPasswordService', $moufManager);
        $Mouf_Security_Password_ForgotYourPasswordController = InstallUtils::getOrCreateInstance('Mouf\\Security\\Password\\ForgotYourPasswordController', 'Mouf\\Security\\Password\\ForgotYourPasswordController', $moufManager);

        // Let's bind instances together.
        if (!$Mouf_Security_Password_PasswordStrengthCheck->getConstructorArgumentProperty('translationService')->isValueSet()) {
            $Mouf_Security_Password_PasswordStrengthCheck->getConstructorArgumentProperty('translationService')->setValue($defaultTranslationService);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordService->getConstructorArgumentProperty('forgetYourPasswordDao')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordService->getConstructorArgumentProperty('forgetYourPasswordDao')->setValue($Mouf_Security_DAO_SecurityUserDao);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordService->getConstructorArgumentProperty('swiftMailer')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordService->getConstructorArgumentProperty('swiftMailer')->setValue($swiftMailer);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordService->getConstructorArgumentProperty('mailTemplate')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordService->getConstructorArgumentProperty('mailTemplate')->setValue($forgotYourPasswordMailTemplate);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordService->getConstructorArgumentProperty('userService')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordService->getConstructorArgumentProperty('userService')->setValue($userService);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('logger')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('logger')->setValue($psr_errorLogLogger);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('template')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('template')->setValue($bootstrapTemplate);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('content')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('content')->setValue($block_content);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('twig')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('twig')->setValue($twigEnvironment);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('forgotYourPasswordService')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('forgotYourPasswordService')->setValue($Mouf_Security_Password_ForgotYourPasswordService);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('userService')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('userService')->setValue($userService);
        }
        if (!$Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('passwordStrengthCheck')->isValueSet()) {
            $Mouf_Security_Password_ForgotYourPasswordController->getConstructorArgumentProperty('passwordStrengthCheck')->setValue($Mouf_Security_Password_PasswordStrengthCheck);
        }

        // Let's rewrite the MoufComponents.php file to save the component
        $moufManager->rewriteMouf();
    }
}
