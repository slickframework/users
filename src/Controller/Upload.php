<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\I18n\TranslateMethods;
use Slick\Mvc\Controller;
use Slick\Mvc\Http\FlashMessagesMethods;
use Slick\Users\Form\PictureFormInterface;
use Slick\Users\Form\UsersForms;
use Slick\Users\Service\Account\PictureUpdaterInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Upload controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Upload extends Controller implements
    DependencyContainerAwareInterface
{

    /**
     * Needed to set the flash messages
     */
    use FlashMessagesMethods;

    /**
     * Used in messages translation
     */
    use TranslateMethods;

    /**
     * Used to retrieve services from dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * @var PictureUpdaterInterface
     */
    protected $pictureService;

    /**
     * @var PictureFormInterface
     */
    protected $form;

    /**
     * Handles the picture upgrade form submission
     */
    public function handle()
    {
        if ($this->getForm()->wasSubmitted()) {

            $this->getPictureService()
                ->setFile(
                    $this->getForm()->getUploadedPicture()
                );

            $this->addSuccessMessage(
                $this->translate(
                    "Your picture was successfully updated."
                )
            );
        }

        $this->redirect($this->getRequest()->getHeaderLine('referer'));
    }

    /**
     * Get picture updater service
     *
     * @return PictureUpdaterInterface
     */
    public function getPictureService()
    {
        if (!$this->pictureService) {
            $this->setPictureService(
                $this->getContainer()->get('pictureUpdater')
            );
        }
        return $this->pictureService;
    }

    /**
     * Set picture updater service
     *
     * @param PictureUpdaterInterface $pictureService
     *
     * @return Upload
     */
    public function setPictureService(PictureUpdaterInterface $pictureService)
    {
        $this->pictureService = $pictureService;
        return $this;
    }

    /**
     * Get form
     *
     * @return PictureFormInterface
     */
    public function getForm()
    {
        if (!$this->form) {
            $this->setForm(UsersForms::getPictureForm());
        }
        return $this->form;
    }

    /**
     * Set form
     *
     * @param PictureFormInterface $form
     *
     * @return Upload
     */
    public function setForm(PictureFormInterface $form)
    {
        $this->form = $form;
        return $this;
    }
}
