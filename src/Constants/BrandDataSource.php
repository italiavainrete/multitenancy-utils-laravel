<?php

namespace IVR\MultiTenancyUtils\Constants;

enum BrandDataSource: string
{

    case DOMAIN = 'domain';

    case SYSTEM = 'system';

    case DEFAULT = 'default';
}
