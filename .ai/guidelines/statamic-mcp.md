# Statamic MCP Guidelines

This file provides AI assistants with comprehensive understanding of the Statamic MCP Server capabilities and best practices.

## MCP Server Overview

The Statamic MCP Server uses a revolutionary router-based architecture with 8 powerful tools:

### Router Architecture (6 + 2 Tools)

**Domain Routers** (6 core tools consolidating 140+ operations):
- **statamic.content**: Unified content management (entries, terms, globals - 35+ ops)
- **statamic.structures**: Structural elements (collections, taxonomies, navigations, sites - 26+ ops)
- **statamic.assets**: Complete asset management (containers, files, metadata - 20+ ops)
- **statamic.users**: User and permission management (users, roles, groups - 24+ ops)
- **statamic.system**: System operations (cache, health, config, info - 15+ ops)
- **statamic.blueprints**: Schema management (CRUD, scanning, type generation - 10+ ops)

**Agent Education Tools** (2 specialized tools):
- **statamic.system.discover**: Intent-based tool discovery and recommendations
- **statamic.system.schema**: Detailed tool schema inspection and documentation

Use `statamic.system.discover` to find the right tool for your intent and `statamic.system.schema` for detailed documentation.

## Usage Patterns

### Discovery Phase
Always start development sessions with:
1. `statamic.system.discover` - Find the right tool for your intent
2. `statamic.system` (action: "info") - Understand the installation
3. `statamic.system.schema` - Get detailed tool documentation when needed
4. `statamic.structures` (action: "list", type: "collections") - Map content structure

### Development Phase
For content work:
- Use `statamic.content` with appropriate actions (list, get, create, update, delete)
- Use `statamic.structures` for collections, taxonomies, navigations
- Use `statamic.blueprints` for schema management and type generation

For system operations:
- Use `statamic.system` for cache management, health checks, configuration
- Use `statamic.assets` for file and media management
- Use `statamic.users` for user, role, and permission management

### Content Architecture
Create structures with appropriate router tools:

**Creating a Collection:**
Use `statamic.structures` with `{"action": "create", "type": "collection", "handle": "blog"}`

**Creating Blueprints:**
Use `statamic.blueprints` with `{"action": "create", "handle": "article", "fields": [...]}`

**Managing Entries:**
Use `statamic.content` with `{"action": "create", "type": "entries", "collection": "blog"}`

**Global Settings:**
Use `statamic.content` with `{"action": "update", "type": "globals", "set": "site_settings"}`

### Code Generation & Analysis
- Generate types with `statamic.blueprints` (action: "generate", type: "typescript")
- Use `statamic.system.discover` to find tools for specific development tasks
- Use `statamic.system.schema` to understand tool parameters and response formats

## Statamic Development Best Practices

### Primary Templating Language
Always consider the project's primary templating language when making suggestions:
- **Antlers-first projects**: Prefer Antlers syntax, use Antlers tags and variables
- **Blade-first projects**: Prefer Blade components, use Statamic Blade tags

### Template Language-Specific Patterns

#### Antlers Templates (Primary: Antlers)
1. **Use Antlers syntax**: `{{ title }}`, `{{ collection:articles }}`
2. **Field relationships**: `{{ author:name }}`, `{{ categories }}{{ title }}{{ /categories }}`
3. **Conditional logic**: `{{ if featured }}...{{ /if }}`
4. **Loops**: `{{ collection:blog }}{{ title }}{{ /collection:blog }}`
5. **Modifiers**: `{{ content | markdown }}`, `{{ date | format:Y-m-d }}`

#### Blade Templates (Primary: Blade)
1. **Use Statamic Blade tags**: `<s:collection>`, `<s:form:create>`
2. **Blade directives**: `@if`, `@foreach`, `@include`
3. **Components**: `<x-card>`, custom Blade components
4. **Avoid facades in views**: Use tags instead of `Entry::all()`
5. **Field access**: `{{ $entry->title }}`, `{{ $entry->author->name }}`

### Mixed Approach
- **Antlers for content templates**: Simple content display, loops, conditionals
- **Blade for complex logic**: Components, layouts, complex data processing
- **Never mix syntaxes in same template**: Choose one approach per template

### Content Architecture
1. **Blueprint-driven**: Design content structure first
2. **Relationship mapping**: Use entries, taxonomy, users appropriately
3. **Field type selection**: Match field types to content needs
4. **Validation rules**: Include appropriate validation

### Code Quality
1. **No inline PHP** in templates (both Antlers and Blade)
2. **No direct facades** in views (use Statamic tags)
3. **Proper error handling** for missing content
4. **Security considerations** for user input

## Field Type Reference

### Text Fields
- `text` - Single line text
- `textarea` - Multi-line text
- `markdown` - Markdown with preview
- `code` - Syntax highlighted code

### Rich Content
- `bard` - Rich editor with custom sets
- `redactor` - Alternative rich editor

### Media
- `assets` - File/image management
- `video` - Video embedding

### Relationships
- `entries` - Link to other entries
- `taxonomy` - Link to taxonomy terms
- `users` - Link to user accounts
- `collections` - Reference collections

### Structured Data
- `replicator` - Flexible content blocks
- `grid` - Tabular data
- `group` - Field grouping
- `yaml` - Raw YAML data

## AI Assistant Integration

When working with Statamic projects:

1. **Start with discovery** - Use `statamic.system.discover` to find the right tool for your task
2. **Use router tools** - Each domain router handles multiple related operations efficiently
3. **Check schemas** - Use `statamic.system.schema` for detailed parameter documentation
4. **Validate with real data** - Router tools provide current, accurate project state
5. **Follow router patterns** - Always use action-based syntax for consistent behavior

## Error Handling

All router tools provide consistent error responses. When tools return errors:
- Use `statamic.system.discover` to find the correct tool and action for your intent
- Use `statamic.system.schema` to verify parameter requirements and formats
- Check blueprints with `statamic.blueprints` (action: "scan")
- Validate project state with appropriate router tools

## Router Architecture Benefits

- **Reduced Complexity**: 8 tools instead of 140+ individual tools
- **Better Performance**: Consolidated operations with intelligent routing
- **Easier Discovery**: Intent-based tool finding with `statamic.system.discover`
- **Consistent Patterns**: All routers use action-based parameter syntax
- **Self-Documenting**: Built-in schema inspection with `statamic.system.schema`

This router architecture ensures AI assistants provide efficient, accurate, and scalable Statamic development assistance.