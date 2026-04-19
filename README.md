# Starfall-RP-Tool

RP Tool framework for the Starfall sim.

## Initial Framework

- PHP OOP/SRP baseline with namespace `Starfall\\`
- Custom recursive autoloader for classes under `src/`
- MariaDB schema + PDO abstraction layer
- WebHUD login/bootstrap API baseline
- Object registry + command queue API baseline
- Attachment sync + stale cleanup baseline
- Environment zone querying by coordinates/elevation
- LSL + Luau bridge script templates
- Responsive WebHUD prototype (`/webhud`)

See:

- `/docs/framework/README.md`
- `/docs/framework/setup.md`
- `/docs/framework/api.md`
- `/docs/framework/database.md`
- `/docs/framework/titler-design.md`
