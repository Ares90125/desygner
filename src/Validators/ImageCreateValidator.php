<?php

namespace App\Validators;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImageCreateValidator
{
    protected ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }
    public function validate(Request $request)
    {
        $constraint = new Assert\Collection([
            'provider'  =>  new Assert\Optional([
                new Assert\Required(),
                new Assert\Length(['max' => 10])
            ]),
            'tags'      =>  new Assert\Optional([
                new Assert\Type('array'),
            ]),
            'image'     =>  new Assert\File([
                'maxSize'   =>  '4096K'
            ]),
            'url'       =>  new Assert\Optional([
                new Assert\Length(['max' => 255])
            ])
        ]);
        $input = $request->request->all();
        $input['image'] = $request->files->get('image');

        $result = $this->validator->validate($input, $constraint);
        if ($result->count()) return $result;

        if (empty($input['image']) && empty($input['url'])) {
            return new ConstraintViolationList([
                new ConstraintViolation('Please input image or url', null, [], $input, 'image', null)
            ]);
        }
        return new ConstraintViolationList([]);
    }
}
